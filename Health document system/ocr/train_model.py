import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import classification_report
import joblib

# Dummy dataset: You can replace with real data later
data = {
    "glucose": [110, 150, 90, 200, 130, 85, 170, 100],
    "cholesterol": [180, 240, 160, 300, 200, 150, 270, 190],
    "ldl": [100, 130, 80, 160, 110, 70, 150, 95],
    "hdl": [50, 40, 60, 35, 45, 65, 30, 55],
    "triglycerides": [120, 200, 100, 250, 180, 90, 230, 140],
    "label": ["Moderate", "High", "Low", "High", "Moderate", "Low", "High", "Moderate"]
}

df = pd.DataFrame(data)

# Encode string labels into numbers
df['label'] = df['label'].map({'Low': 0, 'Moderate': 1, 'High': 2})

# Features and target
X = df.drop("label", axis=1)
y = df["label"]

# Split into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.25, random_state=42)

# Train Random Forest model
model = RandomForestClassifier(random_state=42)
model.fit(X_train, y_train)

# Save the trained model
joblib.dump(model, "health_risk_model.pkl")

# Evaluate model
y_pred = model.predict(X_test)
print(classification_report(y_test, y_pred))
