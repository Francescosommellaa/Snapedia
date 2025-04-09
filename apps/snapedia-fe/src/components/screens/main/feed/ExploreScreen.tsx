import React from "react";
import ArticleFeed from "../../../ui/ArticleFeed";
import {
  fetchMultipleArticles,
  WikipediaArticle,
} from "../../../../../services/wikipediaApi";

interface ExploreScreenProps {
  initialArticles?: WikipediaArticle[];
}

// Funzione per caricare articoli "Esplora" (più influenti del momento)
async function fetchPopularArticles(limit: number) {
  // In futuro potrai implementare la logica per filtrare in base all'influenza
  return fetchMultipleArticles(limit, "it");
}

export default function ExploreScreen({ initialArticles }: ExploreScreenProps) {
  const handleAddCategory = () => {
    console.log("Aggiungi categoria alle preferenze");
    // Integra qui la logica per aggiungere la categoria all'utente
  };

  return (
    <ArticleFeed
      fetchArticles={fetchPopularArticles}
      showCategory={true}
      onAddCategory={handleAddCategory}
      initialArticles={initialArticles}
    />
  );
}
