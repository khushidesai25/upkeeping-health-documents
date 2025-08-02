import sys
import os
import json
import re
import pytesseract
from PIL import Image
from pdf2image import convert_from_path
import joblib
import pandas as pd

# Load ML model
MODEL_PATH = os.path.join(os.path.dirname(__file__), "health_risk_model.pkl")
model = joblib.load(MODEL_PATH)

# Keywords to extract numerical values
keywords = {
    "glucose": r"(glucose|sugar)[^\d]*(\d+)",
    "cholesterol": r"cholesterol[^\d]*(\d+)",
    "ldl": r"ldl[^\d]*(\d+)",
    "hdl": r"hdl[^\d]*(\d+)",
    "triglycerides": r"triglycerides[^\d]*(\d+)"
}

def extract_text(file_path):
    ext = os.path.splitext(file_path)[1].lower()
    if ext in [".png", ".jpg", ".jpeg"]:
        img = Image.open(file_path)
        return pytesseract.image_to_string(img)
    elif ext == ".pdf":
        images = convert_from_path(file_path)
        text = ""
        for img in images:
            text += pytesseract.image_to_string(img)
        return text
    else:
        raise ValueError("Unsupported file type")

def extract_metrics(text):
    text = text.lower()
    extracted = {}
    for key, pattern in keywords.items():
        match = re.search(pattern, text)
        if match:
            try:
                extracted[key] = float(match.group(2))
            except:
                extracted[key] = None
        else:
            extracted[key] = None
    return extracted

def predict_risk(metrics):
    # If any value is missing, model can't predict
    if None in metrics.values():
        return {"error": "Missing values for prediction", "metrics": metrics}

    df = pd.DataFrame([metrics])
    pred = model.predict(df)[0]
    label_map = {0: "Low", 1: "Moderate", 2: "High"}
    return {
        "prediction": label_map[pred],
        "metrics": metrics
    }

def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No file path provided"}))
        return

    file_path = sys.argv[1]
    if not os.path.exists(file_path):
        print(json.dumps({"error": "File not found"}))
        return

    try:
        text = extract_text(file_path)
        metrics = extract_metrics(text)
        result = predict_risk(metrics)
        result["text"] = text.strip()
        print(json.dumps(result, indent=2))
    except Exception as e:
        print(json.dumps({"error": str(e)}))

if __name__ == "__main__":
    main()
