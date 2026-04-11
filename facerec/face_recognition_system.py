import cv2
import os
import numpy as np
from PIL import Image

# ===== TRAIN =====
dataset_path = "dataset"

recognizer = cv2.face.LBPHFaceRecognizer_create()

faces = []
labels = []
label_map = {}
current_label = 0

for person in os.listdir(dataset_path):
    person_path = os.path.join(dataset_path, person)

    if not os.path.isdir(person_path):
        continue

    label_map[current_label] = person

    for img_name in os.listdir(person_path):
        img_path = os.path.join(person_path, img_name)

        img = Image.open(img_path).convert('L')
        img_np = np.array(img, 'uint8')

        faces.append(img_np)
        labels.append(current_label)

    current_label += 1

recognizer.train(faces, np.array(labels))
print("Model Trained ✅")

# ===== CAMERA =====
cam = cv2.VideoCapture(0)
faceCascade = cv2.CascadeClassifier(
    cv2.data.haarcascades + "haarcascade_frontalface_default.xml"
)

print("Starting Face Recognition...")

match_count = 0
fail_count = 0

while True:
    ret, img = cam.read()
    if not ret:
        break

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    faces = faceCascade.detectMultiScale(gray, 1.3, 5)

    for (x, y, w, h) in faces:
        label, confidence = recognizer.predict(gray[y:y+h, x:x+w])
        name = label_map.get(label, "Unknown")

        # 🔐 STRICT SECURITY
        if confidence < 45:
            match_count += 1
            fail_count = 0
        else:
            fail_count += 1
            match_count = 0

        # reset if too many fails
        if fail_count > 10:
            print("❌ Fake or unknown face detected")
            match_count = 0
            fail_count = 0

        # draw UI
        cv2.rectangle(img, (x,y), (x+w,y+h), (0,255,0), 2)
        cv2.putText(img, f"{name} {round(confidence,2)}",
                    (x,y-10), cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0,255,0), 2)

    cv2.imshow("Face Recognition", img)

    # 🔴 HARD CONDITION (no instant access)
    if match_count > 15:
        print("✅ ACCESS GRANTED (SECURE)")
        break

    if cv2.waitKey(1) == 27:
        break

cam.release()
cv2.destroyAllWindows()
