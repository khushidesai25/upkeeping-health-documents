# upkeeping-health-documents
🧾 Health Report Analysis with Machine Learning
This project is a web-based health document analysis system that allows users to upload scanned health reports (PDF or image). The system extracts medical information using OCR and evaluates key health metrics using a trained machine learning model. It then visualizes the results and predicts the health risk level (Low, Moderate, High).
🚀 Features
📤 Upload health reports in PDF or image format

🔍 Extract text using Tesseract OCR

🧠 Predict health risk using a logistic regression ML model

📊 Display extracted metrics and risk levels with Chart.js

🔄 PHP-Python integration via shell_exec()

💾 Stores results as JSON files for display and analysis

health-report-analysis/
├── upload.php              # Handles file upload and calls Python
├── analysis.php            # Displays ML output and charts
├── ocr/
│   └── ocr_analyze.py      # OCR + ML prediction script
├── uploads/                # Uploaded files and output
├── models/
│   └── health_model.pkl    # Trained ML model
└── README.md
🛠 Technologies Used
Frontend: HTML, PHP

Backend: Python, PHP

OCR: pytesseract, pdf2image

ML Model: scikit-learn (Logistic Regression)

Visualization: Chart.js (JavaScript)

📊 Sample Output
Prediction: ✅ Low / Moderate / High Risk

Extracted metrics:

Glucose: 125 mg/dL

Cholesterol: 210 mg/dL

Hemoglobin: 11.5 g/dL

Bar chart shows all metric values

🧠 Machine Learning Details
Input: Extracted health metrics from text

Model: Logistic Regression (trained on synthetic dataset)

Output: Risk classification (Low, Moderate, High)

🔧 Installation & Run Instructions
Requirements
PHP ≥ 7.0

Python ≥ 3.7

Tesseract OCR installed and added to PATH

Python packages:

nginx
Copy
Edit
pip install pytesseract pdf2image scikit-learn matplotlib
Run the App
Place project files in your XAMPP/htdocs folder or any PHP server

Start Apache (e.g., using XAMPP control panel)

Visit: http://localhost/your-folder/upload.php

Upload a sample report and view the analysis

⚠️ Known Limitations
OCR accuracy depends on image quality



📌 To Do / Future Work
Use advanced models (Random Forest, XGBoost)

Improve frontend design with Bootstrap

Connect to real hospital/lab APIs
