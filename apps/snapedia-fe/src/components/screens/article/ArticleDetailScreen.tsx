import React, { useEffect, useState } from "react";
import { View, Text, Image, ScrollView, StyleSheet } from "react-native";
import { RouteProp, useRoute } from "@react-navigation/native";
import {
  WikipediaArticle,
  getArticleByTitle,
} from "../../../../services/wikipediaApi";
import Icon from "../../ui/Icon";

import { RootStackParamList } from "../../../../types/types"; // <-- path corretto al tuo file

type ArticleDetailRouteProp = RouteProp<RootStackParamList, "ArticleDetail">;

export const ArticleDetailScreen = () => {
  const route = useRoute<ArticleDetailRouteProp>();
  const { title } = route.params;

  const [article, setArticle] = useState<WikipediaArticle | null>(null);

  useEffect(() => {
    const fetchData = async () => {
      const article = await getArticleByTitle(title);
      setArticle(article);
    };
    fetchData();
  }, [title]);

  if (!article) return <Text style={styles.loading}>Caricamento...</Text>;

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.title}>{article.title}</Text>
      <Text style={styles.category}>{article.category}</Text>

      <Image source={{ uri: article.thumbnail?.source }} style={styles.image} />
      <Text style={styles.description}>{article.extract}</Text>

      <View style={styles.actions}>
        <Icon name="like" size={28} />
        <Icon name="comment" size={28} />
        <Icon name="share" size={28} />
        <Icon name="save" size={28} />
      </View>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: { backgroundColor: "#000", flex: 1, padding: 16 },
  title: { fontSize: 24, fontWeight: "bold", color: "#fff" },
  category: { color: "#ccc", marginBottom: 12 },
  image: { width: "100%", height: 250, borderRadius: 12, marginBottom: 16 },
  description: { color: "#fff", fontSize: 15, lineHeight: 22 },
  actions: {
    flexDirection: "column",
    alignItems: "flex-end",
    marginTop: 20,
    gap: 18,
  },
  loading: { color: "#fff", textAlign: "center", marginTop: 50 },
});
