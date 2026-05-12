import os
import cv2
import numpy as np
from PIL import Image

DATASET_PATH = "dataset"
MODEL_PATH = "trainer.yml"
LABELS_PATH = "labels.npy"


def train_model():
    print("\n🔄 Training model...")

    recognizer = cv2.face.LBPHFaceRecognizer_create()

    faces = []
    labels = []
    label_map = {}
    current_label = 0

    if not os.path.exists(DATASET_PATH):
        print("❌ Dataset folder not found")
        return

    for person in os.listdir(DATASET_PATH):
        person_path = os.path.join(DATASET_PATH, person)

        if not os.path.isdir(person_path):
            continue

        print(f"📂 Processing: {person}")
        label_map[current_label] = person

        for img_name in os.listdir(person_path):
            img_path = os.path.join(person_path, img_name)

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

    recognizer.train(faces, np.array(labels))
    recognizer.save(MODEL_PATH)

    # Save label map
    np.save(LABELS_PATH, label_map)

    print("✅ Model trained & saved (trainer.yml)")


def reset_model():
    if os.path.exists(MODEL_PATH):
        os.remove(MODEL_PATH)
        print("🗑️ Model deleted")

    if os.path.exists(LABELS_PATH):
        os.remove(LABELS_PATH)
        print("🗑️ Labels deleted")


def check_dataset():
    print("\n📊 Dataset info:")

    if not os.path.exists(DATASET_PATH):
        print("❌ Dataset folder missing")
        return

    for person in os.listdir(DATASET_PATH):
        person_path = os.path.join(DATASET_PATH, person)

        if os.path.isdir(person_path):
            count = len(os.listdir(person_path))
            print(f"{person}: {count} images")


if __name__ == "__main__":
    print("\n=== MODEL MANAGER ===")
    print("1. Train model")
    print("2. Reset model")
    print("3. Check dataset")

    choice = input("Choose option: ")

    if choice == "1":
        train_model()
    elif choice == "2":
        reset_model()
    elif choice == "3":
        check_dataset()
    else:
        print("❌ Invalid choice")