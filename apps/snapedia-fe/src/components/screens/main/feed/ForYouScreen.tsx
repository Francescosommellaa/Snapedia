import React from "react";
import ArticleFeed from "../../../ui/ArticleFeed";
import {
  fetchMultipleArticles,
  WikipediaArticle,
} from "../../../../../services/wikipediaApi";

interface ForYouScreenProps {
  initialArticles?: WikipediaArticle[];
}

// Funzione per caricare articoli "For You" (basata sulle categorie dell'utente)
async function fetchArticlesForUser(limit: number) {
  // Qui potrai integrare la logica basata sulle categorie scelte
  return fetchMultipleArticles(limit, "it");
}

export default function ForYouScreen({ initialArticles }: ForYouScreenProps) {
  return (
    <ArticleFeed
      fetchArticles={fetchArticlesForUser}
      showCategory={false}
      initialArticles={initialArticles}
    />
  );
}
