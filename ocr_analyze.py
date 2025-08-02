import sys
import json
import os
import pytesseract
from PIL import Image
from pdf2image import convert_from_path

def extract_text_from_image(image_path):
    try:
        img = Image.open(image_path)
        text = pytesseract.image_to_string(img)
        return text
    except Exception as e:
        return f"Error reading image: {str(e)}"

def extract_text_from_pdf(pdf_path):
    try:
        images = convert_from_path(pdf_path)
        text = ""
        for page in images:
            text += pytesseract.image_to_string(page)
        return text
    except Exception as e:
        return f"Error reading PDF: {str(e)}"

def simulate_analysis(text):
    # Dummy analysis: check for keywords
    results = {}
    if "glucose" in text.lower():
        results["Diabetes Risk"] = "High"
    if "hemoglobin" in text.lower():
        results["Anemia Risk"] = "Moderate"
    if "cholesterol" in text.lower():
        results["Heart Risk"] = "Moderate"
    if not results:
        results["Note"] = "No significant markers found"
    return results

def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No file path provided"}))
        return

    file_path = sys.argv[1]
    if not os.path.exists(file_path):
        print(json.dumps({"error": "File does not exist"}))
        return

    ext = os.path.splitext(file_path)[1].lower()
    if ext in ['.png', '.jpg', '.jpeg']:
        text = extract_text_from_image(file_path)
    elif ext == '.pdf':
        text = extract_text_from_pdf(file_path)
    else:
        print(json.dumps({"error": "Unsupported file type"}))
        return

    predictions = simulate_analysis(text)

    result = {
        "text": text.strip(),
        "predictions": predictions
    }

    print(json.dumps(result, indent=2))

if __name__ == "__main__":
    main()
