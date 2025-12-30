========================================================================
PROJECT: Denica Perfumes - Web Application Prototype
MODULE: Scent Discovery Quiz
========================================================================

HOW TO RUN THE QUIZ:
1. Open the "index.html" file in Google Chrome.
2. Click on "Discovery" in the navigation bar OR click "Take Quiz" on the homepage.
3. Alternatively, you can open "quiz_questions.html" directly.

TECHNICAL NOTES:
- Independent CSS: This folder has its own independent CSS file (quiz_style.css). It does not rely on global styles, ensuring the quiz looks correct even if moved.
- No Database Dependency: This module does not rely on backend databases. All questions and logic are hardcoded directly into the file. This ensures the quiz functions 100% reliably during the evaluation without needing a server connection.

FEATURES IMPLEMENTED:
- Full interactive Frontend Logic: The quiz tracks user answers (A, B, C, D, E).
- Algorithm: Automatically calculates the "Winning Personality" based on the majority of answers.
- Dynamic Results: Displays the correct personality description and matching product recommendation based on the score.
- Mobile Responsive: The layout adjusts for phone screens.

NOTE ON SAVING:
For this prototype submission, the "Save to Profile" feature is simulated using a JavaScript alert to demonstrate the user flow. The backend integration (PHP/MySQL) is decoupled from this frontend display to ensure stability.

BROWSER COMPATIBILITY:
Tested and verified on Google Chrome (Version 120+).
