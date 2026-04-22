from flask import Flask, request, jsonify
from flask_cors import CORS
import cv2
import numpy as np
import os
from PIL import Image

app = Flask(__name__)
CORS(app)  # allow frontend (PHP/HTML) to call API

# ===== TRAIN MODEL =====
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

        try:
            img = Image.open(img_path).convert('L')
        except:
            continue

        img_np = np.array(img, 'uint8')

        faces.append(img_np)
        labels.append(current_label)

    current_label += 1

# train only if data exists
if len(faces) == 0:
    print("❌ No dataset found. Please add images first.")
else:
    recognizer.train(faces, np.array(labels))
    print("✅ Model trained successfully!")

# ===== FACE DETECTOR =====
faceCascade = cv2.CascadeClassifier(
    cv2.data.haarcascades + "haarcascade_frontalface_default.xml"
)

# ===== API ROUTE =====
@app.route('/verify', methods=['POST'])
def verify():
    if 'image' not in request.files:
        return jsonify({"status": "error", "message": "No image uploaded"})

    file = request.files['image']

    # convert image
    npimg = np.frombuffer(file.read(), np.uint8)
    img = cv2.imdecode(npimg, cv2.IMREAD_COLOR)

    if img is None:
        return jsonify({"status": "error", "message": "Invalid image"})

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    faces_detected = faceCascade.detectMultiScale(gray, 1.3, 5)

    if len(faces_detected) == 0:
        return jsonify({"status": "denied", "message": "No face detected"})

    for (x, y, w, h) in faces_detected:
        label, confidence = recognizer.predict(gray[y:y+h, x:x+w])
        name = label_map.get(label, "Unknown")

        print(f"Detected: {name}, Confidence: {confidence}")

        # 🔐 Adjust threshold if needed
        if confidence < 55:
            return jsonify({
                "status": "granted",
                "user": name,
                "confidence": float(confidence)
            })

    return jsonify({"status": "denied", "message": "Face not recognized"})


# ===== RUN SERVER =====
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)