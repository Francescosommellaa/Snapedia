import React from "react";
import {
  View,
  Text,
  Image,
  Animated,
  TouchableOpacity,
  StyleSheet,
} from "react-native";
import Icon from "../ui/Icon";
import { WikipediaArticle } from "../../../services/wikipediaApi";
import { useTypedNavigation } from "../../hooks/navigation/useTypedNavigation";

interface ArticleItemProps {
  article: WikipediaArticle;
  index: number;
  currentIndex: number;
  scrollY: Animated.Value;
  ITEM_HEIGHT: number;
  showCategory?: boolean;
  onAddCategory?: () => void;
}

export default function ArticleItem({
  article,
  index,
  currentIndex,
  scrollY,
  ITEM_HEIGHT,
  showCategory = false,
  onAddCategory,
}: ArticleItemProps) {
  const navigation = useTypedNavigation();
  const isCurrent = index === currentIndex;
  const overlayOpacity = isCurrent
    ? scrollY.interpolate({
        inputRange: [
          (index - 1) * ITEM_HEIGHT + ITEM_HEIGHT * 0.28,
          index * ITEM_HEIGHT,
          (index + 1) * ITEM_HEIGHT - ITEM_HEIGHT * 0.28,
        ],
        outputRange: [0.8, 0, 0.8],
        extrapolate: "clamp",
      })
    : new Animated.Value(0);
  return (
    <View style={[styles.page, { height: ITEM_HEIGHT }]}>
      {article.thumbnail?.source && (
        <Image
          source={{ uri: article.thumbnail.source }}
          style={styles.image}
          resizeMode="cover"
        />
      )}
      {/* Overlay con effetto blur */}
      <Animated.View style={[styles.blurOverlay, { opacity: overlayOpacity }]}>
        <View style={styles.darkOverlay} />
      </Animated.View>

      {/* Overlay gradient */}
      <View style={styles.gradientOverlay} />

      {/* Contenuto (testo e azioni) */}
      <View style={styles.overlay}>
        <View style={styles.text}>
          {showCategory ? (
            <View style={styles.categoryContainer}>
              <Text style={styles.categoryText}>Categoria</Text>
              <TouchableOpacity onPress={onAddCategory}>
                <Icon name="plus" size={16} />
              </TouchableOpacity>
            </View>
          ) : (
            <Text style={styles.category}>Categoria</Text>
          )}
          <Text style={styles.title}>{article.title}</Text>
          <Text style={styles.intro}>{article.extract}</Text>
        </View>

        {/* Azioni: Like, Comment, Save, Share, Expand */}
        <View style={styles.actionsContainer}>
          <TouchableOpacity onPress={() => console.log("Like")}>
            <Icon name="like" size={28} />
          </TouchableOpacity>
          <TouchableOpacity onPress={() => console.log("Comment")}>
            <Icon name="comment" size={28} />
          </TouchableOpacity>
          <TouchableOpacity onPress={() => console.log("Save")}>
            <Icon name="save" size={28} />
          </TouchableOpacity>
          <TouchableOpacity onPress={() => console.log("Share")}>
            <Icon name="share" size={28} />
          </TouchableOpacity>
          <TouchableOpacity>
            <Icon
              name="expand"
              size={28}
              onPress={() =>
                navigation.navigate("ArticleDetail", {
                  id: article.id,
                  title: article.title,
                })
              }
            />
          </TouchableOpacity>
        </View>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  page: {
    height: "100%",
    position: "relative",
    backgroundColor: "#000",
  },
  image: {
    ...StyleSheet.absoluteFillObject,
    width: "100%",
    height: "100%",
  },
  blurOverlay: {
    ...StyleSheet.absoluteFillObject,
    zIndex: 2,
  },
  darkOverlay: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: "rgba(0, 0, 0, 0.9)",
    zIndex: 2,
  },
  gradientOverlay: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: "rgba(0,0,0,0.26)",
    zIndex: 1,
  },
  overlay: {
    position: "absolute",
    bottom: 24,
    left: 24,
    right: 24,
    zIndex: 3,
    flexDirection: "row",
    alignItems: "flex-end",
    justifyContent: "space-between",
    marginBottom: 80,
  },
  text: {
    maxWidth: "80%",
  },
  category: {
    color: "#2DFFD5",
    fontWeight: "600",
    fontSize: 15,
    marginBottom: 4,
  },
  title: {
    color: "#fff",
    fontSize: 32,
    fontWeight: "bold",
    marginBottom: 8,
  },
  intro: {
    color: "#D1D4D8",
    fontSize: 16,
  },
  actionsContainer: {
    flexDirection: "column",
    gap: 32,
    alignItems: "flex-end",
    maxWidth: "20%",
  },
  categoryContainer: {
    flexDirection: "row",
    alignItems: "center",
    overflow: "hidden",
    borderRadius: 40,
    marginBottom: 8,
    paddingHorizontal: 12,
    paddingVertical: 6,
    backgroundColor: "rgba(0, 0, 0, 0.4)",
    alignSelf: "flex-start",
  },
  categoryText: {
    color: "#2DFFD5",
    fontWeight: "600",
    fontSize: 15,
    marginRight: 8,
  },
});
