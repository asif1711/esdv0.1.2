import cv2
import numpy as np
import os
from tkinter import messagebox
import tkinter as tk

face_cascade = cv2.CascadeClassifier("haarcascade_frontalface_default.xml")
DATASET_PATH = "dataset"


# ---------- LOAD USERS ----------
def load_users():

    users = []
    orb = cv2.ORB_create()

    for file in os.listdir(DATASET_PATH):

        if file.endswith(".jpg"):

            username = file.split(".")[0]
            path = os.path.join(DATASET_PATH, file)

            img = cv2.imread(path, 0)
            faces = face_cascade.detectMultiScale(img, 1.3, 5)

            if len(faces) == 0:
                continue

            (x, y, w, h) = faces[0]
            face = img[y:y+h, x:x+w]
            face = cv2.resize(face, (300, 300))

            kp, des = orb.detectAndCompute(face, None)

            users.append({
                "name": username,
                "kp": kp,
                "des": des
            })

    return users


# ---------- VERIFY ----------
def verify_face():

    users = load_users()

    orb = cv2.ORB_create()
    cap = cv2.VideoCapture(0)

    prev_x = None
    movement = 0
    verified_user = None

    while True:
        ret, frame = cap.read()
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

        faces = face_cascade.detectMultiScale(gray, 1.3, 5)

        for (x, y, w, h) in faces:

            # movement detection
            if prev_x is not None:
                movement += abs(x - prev_x)

            prev_x = x

            face = gray[y:y+h, x:x+w]
            face = cv2.resize(face, (300, 300))

            kp2, des2 = orb.detectAndCompute(face, None)

            if des2 is None:
                continue

            scores = []

            for user in users:

                bf = cv2.BFMatcher(cv2.NORM_HAMMING, crossCheck=True)
                matches = bf.match(user["des"], des2)

                score = len(matches)
                similarity = score / len(user["kp"])

                scores.append((user["name"], score, similarity))

            # sort by score
            scores.sort(key=lambda x: x[1], reverse=True)

            best_user, best_score, best_similarity = scores[0]

            # confidence check
            if len(scores) > 1:
                second_score = scores[1][1]
            else:
                second_score = 0

            confidence_gap = best_score - second_score

            # ---------- STRICT SECURITY ----------
            if (
                best_score > 30 and
                best_similarity > 0.12 and
                movement > 30 and
                confidence_gap > 8
            ):
                verified_user = best_user
                text = f"LOGIN SUCCESS: {best_user}"
                color = (0,255,0)
            else:
                text = "ACCESS DENIED"
                color = (0,0,255)

            cv2.rectangle(frame,(x,y),(x+w,y+h),color,2)
            cv2.putText(frame,text,(x,y-10),
                        cv2.FONT_HERSHEY_SIMPLEX,0.8,color,2)

        cv2.imshow("Face Login", frame)

        if verified_user:
            cv2.waitKey(3000)
            break

        if cv2.waitKey(1) == 27:
            break

    cap.release()
    cv2.destroyAllWindows()

    return verified_user


# ---------- LOGIN ----------
def login():

    user = verify_face()

    if user:
        messagebox.showinfo("Success", f"Login Successful: {user}")
    else:
        messagebox.showerror("Error", "Face Not Matched")


# ---------- GUI ----------
root = tk.Tk()
root.title("Multi User Face Login")
root.geometry("300x150")

tk.Label(root, text="Click Login and Move Face").pack(pady=20)
tk.Button(root, text="Login", command=login).pack(pady=20)

root.mainloop()
