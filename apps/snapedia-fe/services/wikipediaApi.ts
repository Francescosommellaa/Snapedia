import { setImageWidth } from "../src/utils/imageUtils";
import { slugify } from "../src/utils/slugify";

export type WikipediaArticle = {
  id: string;
  title: string;
  extract: string;
  category?: string;
  thumbnail?: { source: string };
  content_urls: {
    mobile: { page: string };
  };
};

async function getAdaptiveImageWidth(): Promise<number> {
  if (typeof navigator !== "undefined" && "connection" in navigator) {
    const conn = navigator.connection as any;
    const downSpeed = conn.downlink || 0;
    const rtt = conn.rtt || 0;

    if (downSpeed > 10 && rtt < 100) return 1200;
    if (downSpeed > 3) return 800;
    return 500;
  }

  return 800;
}

export async function fetchRandomArticle(lang: string = "it"): Promise<WikipediaArticle> {
  const response = await fetch(`https://${lang}.wikipedia.org/api/rest_v1/page/random/summary`);
  if (!response.ok) {
    throw new Error("Errore nel recupero dell’articolo");
  }

  const data = await response.json();

  if (data.thumbnail?.source) {
    const width = await getAdaptiveImageWidth();
    data.thumbnail.source = setImageWidth(data.thumbnail.source, width);
  }

  const article: WikipediaArticle = {
    id: slugify(data.title),
    title: data.title,
    extract: data.extract,
    thumbnail: data.thumbnail,
    content_urls: data.content_urls
  };

  return article;
}

export async function fetchMultipleArticles(count: number = 5, lang: string = "it"): Promise<WikipediaArticle[]> {
  const articles: WikipediaArticle[] = [];

  for (let i = 0; i < count; i++) {
    try {
      const article = await fetchRandomArticle(lang);
      articles.push(article);
    } catch (error) {
      console.error("Errore nel prefetch di un articolo", error);
    }
  }

  return articles;
}

export async function getArticleByTitle(title: string): Promise<WikipediaArticle | null> {
  try {
    const summaryRes = await fetch(`https://it.wikipedia.org/api/rest_v1/page/summary/${encodeURIComponent(title)}`);
    if (!summaryRes.ok) throw new Error("Articolo non trovato");

    const data = await summaryRes.json();
    // costruisci l'articolo come prima...

    return {
      id: slugify(data.title),
      title: data.title,
      extract: data.extract,
      thumbnail: data.thumbnail,
      content_urls: data.content_urls,
    };
  } catch (error) {
    console.error("Errore nel recupero dettagli articolo:", error);
    return null;
  }
}
