import os
import cv2
import numpy as np
from PIL import Image
import mysql.connector

DATASET_PATH = "dataset"
MODEL_PATH = "trainer.yml"
LABELS_PATH = "labels.npy"


# ==================================
# DATABASE CONNECTION
# ==================================

def get_db_connection():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="vips"
    )


# ==================================
# GET PENDING USERS
# ==================================

def get_pending_users():

    pending_users = []

    try:

        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)

        cursor.execute("""
            SELECT
                name,
                email
            FROM users
            WHERE dataset_generated = 1
              AND trained = 0
        """)

        users = cursor.fetchall()

        for user in users:

            dataset_path = os.path.join(
                DATASET_PATH,
                user["email"]
            )

            if os.path.isdir(dataset_path):

                pending_users.append({
                    "name": user["name"],
                    "email": user["email"],
                    "dataset_path": dataset_path
                })

        cursor.close()
        conn.close()

    except Exception as e:

        print(f"❌ Database error: {e}")

    return pending_users


# ==================================
# TRAIN MODEL
# ==================================

def train_model():

    pending_users = get_pending_users()

    if len(pending_users) == 0:

        print("\n⚠ No pending users found.")
        return

    print("\n===================================")
    print("PENDING USERS FOR TRAINING")
    print("===================================\n")

    for index, user in enumerate(pending_users, start=1):

        print(f"{index}. {user['name']}")
        print(f"   Email   : {user['email']}")
        print(f"   Dataset : {user['dataset_path']}")
        print()

    print("===================================\n")

    confirm = input("Train these users? (Y/N): ").strip().upper()

    if confirm != "Y":

        print("❌ Training cancelled.")
        return

    print("\n🔄 Training model...")

    recognizer = cv2.face.LBPHFaceRecognizer_create()

    faces = []
    labels = []
    label_map = {}

    current_label = 0

    if not os.path.exists(DATASET_PATH):

        print("❌ Dataset folder not found")
        return

    # ==================================
    # ORIGINAL TRAINING LOGIC
    # (UNCHANGED)
    # ==================================

    for person in os.listdir(DATASET_PATH):

        person_path = os.path.join(
            DATASET_PATH,
            person
        )

        if not os.path.isdir(person_path):
            continue

        print(f"📂 Processing: {person}")

        label_map[current_label] = person

        for img_name in os.listdir(person_path):

            img_path = os.path.join(
                person_path,
                img_name
            )

            try:

                img = Image.open(img_path).convert('L')
                img_np = np.array(img, 'uint8')

            except:

                continue

            faces.append(img_np)
            labels.append(current_label)

        current_label += 1

    if len(faces) == 0:

        print("❌ No dataset found. Cannot train.")
        return

    recognizer.train(
        faces,
        np.array(labels)
    )

    recognizer.save(MODEL_PATH)

    np.save(
        LABELS_PATH,
        label_map
    )

    print("✅ Model trained & saved (trainer.yml)")

    # ==================================
    # UPDATE USERS
    # ==================================

    try:

        conn = get_db_connection()
        cursor = conn.cursor()

        updated_count = 0

        print("\n===================================")
        print("TRAINING SUCCESSFUL")
        print("===================================\n")

        for user in pending_users:

            cursor.execute("""
                UPDATE users
                SET
                    trained = 1,
                    dataset_path = %s
                WHERE email = %s
            """, (
                user["dataset_path"],
                user["email"]
            ))

            updated_count += 1

            print(f"✅ {user['name']}")
            print(f"   Email   : {user['email']}")
            print(f"   Dataset : {user['dataset_path']}")
            print()

        conn.commit()

        cursor.close()
        conn.close()

        print("===================================")
        print(f"✅ {updated_count} user(s) updated")
        print("===================================\n")

    except Exception as e:

        print(f"❌ Database update failed: {e}")


# ==================================
# RESET MODEL
# ==================================

def reset_model():

    if os.path.exists(MODEL_PATH):

        os.remove(MODEL_PATH)

        print("🗑️ Model deleted")

    if os.path.exists(LABELS_PATH):

        os.remove(LABELS_PATH)

        print("🗑️ Labels deleted")


# ==================================
# CHECK MODEL
# ==================================

def check_model():

    print("\n===================================")
    print("MODEL STATUS")
    print("===================================\n")

    print(
        f"trainer.yml : {'FOUND' if os.path.exists(MODEL_PATH) else 'MISSING'}"
    )

    print(
        f"labels.npy  : {'FOUND' if os.path.exists(LABELS_PATH) else 'MISSING'}"
    )

    print("\n===================================")
    print("DATASET FOLDERS")
    print("===================================\n")

    if not os.path.exists(DATASET_PATH):

        print("❌ Dataset folder missing")
        return

    for person in os.listdir(DATASET_PATH):

        person_path = os.path.join(
            DATASET_PATH,
            person
        )

        if os.path.isdir(person_path):

            count = len(os.listdir(person_path))

            print(
                f"{person}: {count} images"
            )


# ==================================
# MAIN MENU
# ==================================

if __name__ == "__main__":

    print("\n=== MODEL MANAGER ===")
    print("1. Train Model")
    print("2. Reset Model")
    print("3. Check Model")

    choice = input("\nChoose option: ")

    if choice == "1":

        train_model()

    elif choice == "2":

        reset_model()

    elif choice == "3":

        check_model()

    else:

        print("❌ Invalid choice")