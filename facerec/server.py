from flask import Flask, request, jsonify
from flask_cors import CORS
import cv2
import numpy as np
import os

app = Flask(__name__)
CORS(app)

# ===== PATHS =====
MODEL_PATH = "trainer.yml"
LABELS_PATH = "labels.npy"

recognizer = cv2.face.LBPHFaceRecognizer_create()
label_map = {}
model_trained = False

# ===== LOAD MODEL =====
def load_model():
    global recognizer, label_map, model_trained

    if os.path.exists(MODEL_PATH) and os.path.exists(LABELS_PATH):
        recognizer.read(MODEL_PATH)
        label_map = np.load(LABELS_PATH, allow_pickle=True).item()
        model_trained = True
        print("✅ Model loaded successfully!")
    else:
        model_trained = False
        print("❌ No trained model found")

# Load at startup
load_model()

# ===== FACE DETECTOR =====
faceCascade = cv2.CascadeClassifier(
    cv2.data.haarcascades + "haarcascade_frontalface_default.xml"
)

# ===== VERIFY ROUTE =====
@app.route('/verify', methods=['POST'])
def verify():

    # 🔴 No model
    if not model_trained:
        return jsonify({
            "status": "error",
            "message": "No trained model available"
        }), 200

    if 'image' not in request.files:
        return jsonify({
            "status": "error",
            "message": "No image uploaded"
        }), 200

    file = request.files['image']

    # convert image
    npimg = np.frombuffer(file.read(), np.uint8)
    img = cv2.imdecode(npimg, cv2.IMREAD_COLOR)

    if img is None:
        return jsonify({
            "status": "error",
            "message": "Invalid image"
        }), 200

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    faces = faceCascade.detectMultiScale(gray, 1.3, 5)

    if len(faces) == 0:
        return jsonify({
            "status": "denied",
            "user": "Unknown",
            "message": "No face detected"
        }), 200

    # 🎯 Thresholds
    ACCEPT_THRESHOLD = 75
    REJECT_THRESHOLD = 90

    for (x, y, w, h) in faces:
        try:
            label, confidence = recognizer.predict(gray[y:y+h, x:x+w])
        except:
            return jsonify({
                "status": "error",
                "message": "Model error"
            }), 200

        name = label_map.get(label, "Unknown")

        print(f"Detected: {name}, Confidence: {confidence}")

        # ✅ Strong match → ACCEPT
        if confidence < ACCEPT_THRESHOLD:
            return jsonify({
                "status": "granted",
                "user": name,
                "confidence": float(confidence)
            }), 200

        # ❌ Weak match → UNKNOWN
        elif confidence >= REJECT_THRESHOLD:
            return jsonify({
                "status": "denied",
                "user": "Unknown",
                "confidence": float(confidence),
                "message": "Unknown user"
            }), 200

    # fallback
    return jsonify({
        "status": "denied",
        "user": "Unknown",
        "message": "Face not recognized"
    }), 200

@app.route('/capture', methods=['POST'])
def capture():

    if 'image' not in request.files:
        return jsonify({
            "status": "error",
            "message": "No image uploaded"
        }), 400

    email = request.form.get("email")

    if not email:
        return jsonify({
            "status": "error",
            "message": "Email missing"
        }), 400

    file = request.files['image']

    npimg = np.frombuffer(file.read(), np.uint8)
    img = cv2.imdecode(npimg, cv2.IMREAD_COLOR)

    if img is None:
        return jsonify({
            "status": "error",
            "message": "Invalid image"
        }), 400

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    faces = faceCascade.detectMultiScale(gray, 1.3, 5)

    if len(faces) == 0:
        return jsonify({
            "status": "error",
            "message": "No face detected"
        }), 200

    dataset_dir = os.path.join("dataset", email)

    os.makedirs(dataset_dir, exist_ok=True)

    count = len(os.listdir(dataset_dir))

    for (x, y, w, h) in faces:

        face = gray[y:y+h, x:x+w]

        img_path = os.path.join(
            dataset_dir,
            f"{count + 1}.jpg"
        )

        cv2.imwrite(img_path, face)

        return jsonify({
            "status": "success",
            "count": count + 1
        }), 200

    return jsonify({
        "status": "error",
        "message": "Capture failed"
    }), 200

# ===== RETRAIN API =====
@app.route('/retrain', methods=['POST'])
def retrain():
    os.system("python manage_model.py")
    load_model()
    return jsonify({
        "status": "success",
        "message": "Model retrained"
    })


# ===== RUN =====
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)