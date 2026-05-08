#!/bin/bash
# Script to automatically commit and push changes

# Navigate to the project directory
cd "/Applications/MAMP/htdocs/OnlineSolutionsRx"

# Check if there are any changes
if [[ -n $(git status -s) ]]; then
    echo "Changes detected. Committing and pushing..."
    git add .
    git commit -m "Auto-commit: $(date +'%Y-%m-%d %H:%M:%S')"
    git push origin main
else
    echo "No changes detected."
fi
