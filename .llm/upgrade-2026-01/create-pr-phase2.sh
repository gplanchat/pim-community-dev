#!/bin/bash
# Script pour créer la PR GitHub pour Phase 2 - PHP 8.1 → 8.4 migration
# Usage: GITHUB_TOKEN=your_token ./create-pr-phase2.sh

set -e

REPO="gplanchat/pim-community-dev"
BRANCH="feature/upgrade-2026-01-php-8.4"
BASE="master"
TITLE="feat(upgrade): Phase 2 - PHP 8.1 → 8.4 migration"
BODY_FILE=".llm/upgrade-2026-01/PR-description-phase2.md"

if [ -z "$GITHUB_TOKEN" ]; then
    echo "❌ Erreur: GITHUB_TOKEN n'est pas défini"
    echo "Usage: GITHUB_TOKEN=your_token $0"
    echo ""
    echo "Vous pouvez créer la PR manuellement sur GitHub:"
    echo "https://github.com/$REPO/compare/$BASE...$BRANCH"
    exit 1
fi

echo "🔨 Création de la PR GitHub..."
echo "Repository: $REPO"
echo "Branche: $BRANCH"
echo "Base: $BASE"
echo ""

# Créer la PR via l'API GitHub
RESPONSE=$(curl -s -X POST \
  -H "Authorization: token $GITHUB_TOKEN" \
  -H "Accept: application/vnd.github.v3+json" \
  "https://api.github.com/repos/$REPO/pulls" \
  -d "{
    \"title\": \"$TITLE\",
    \"head\": \"$BRANCH\",
    \"base\": \"$BASE\",
    \"body\": $(cat "$BODY_FILE" | jq -Rs .)
  }")

# Vérifier si la PR a été créée
PR_URL=$(echo "$RESPONSE" | jq -r '.html_url // empty')
PR_NUMBER=$(echo "$RESPONSE" | jq -r '.number // empty')

if [ -n "$PR_URL" ] && [ "$PR_URL" != "null" ]; then
    echo "✅ PR créée avec succès!"
    echo "📝 PR #$PR_NUMBER: $PR_URL"
    echo ""
    echo "Mettez à jour le tracking avec:"
    echo "  PR URL: $PR_URL"
    echo "  PR Number: #$PR_NUMBER"
else
    echo "❌ Erreur lors de la création de la PR"
    echo "Réponse: $RESPONSE"
    exit 1
fi
