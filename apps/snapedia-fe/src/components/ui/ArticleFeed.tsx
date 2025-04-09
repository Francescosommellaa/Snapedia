import React, { useEffect, useState, useRef } from "react";
import {
  View,
  ActivityIndicator,
  Dimensions,
  Animated,
  StyleSheet,
  NativeSyntheticEvent,
  NativeScrollEvent,
} from "react-native";
import { useSafeAreaInsets } from "react-native-safe-area-context";
import { WikipediaArticle } from "../../../services/wikipediaApi";
import ArticleItem from "./ArticleItem";

interface ArticleFeedProps {
  fetchArticles: (limit: number) => Promise<WikipediaArticle[]>;
  showCategory?: boolean;
  onAddCategory?: () => void;
  initialArticles?: WikipediaArticle[];
}

const ITEM_HEIGHT = Dimensions.get("window").height;

export default function ArticleFeed({
  fetchArticles,
  showCategory = false,
  onAddCategory,
  initialArticles,
}: ArticleFeedProps) {
  const insets = useSafeAreaInsets();
  const [articles, setArticles] = useState<WikipediaArticle[]>([]);
  const [nextArticles, setNextArticles] = useState<WikipediaArticle[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [currentIndex, setCurrentIndex] = useState(0);
  const [isFetchingMore, setIsFetchingMore] = useState(false);

  const scrollY = useRef(new Animated.Value(-1000)).current;
  const pullDistance = useRef(0);

  const viewabilityConfig = { itemVisiblePercentThreshold: 80 };

  useEffect(() => {
    async function initialize() {
      if (initialArticles && initialArticles.length > 0) {
        // Se sono stati passati initialArticles, li usiamo subito
        setArticles(initialArticles);
        // Non facciamo prefetch dei nextArticles: il caricamento avverrà al primo scroll
        setLoading(false);
      } else {
        // Se non ci sono initialArticles, carichiamo normalmente 5 articoli iniziali
        loadInitialArticles();
      }
    }
    initialize();
  }, []);

  useEffect(() => {
    if (!loading) scrollY.setValue(0);
  }, [loading]);

  async function loadInitialArticles() {
    setLoading(true);
    const initial = await fetchArticles(5);
    const next = await fetchArticles(5);
    setArticles(initial);
    setNextArticles(next);
    setLoading(false);
  }

  async function refreshArticles() {
    if (refreshing) return;
    setRefreshing(true);
    const fresh = await fetchArticles(5);
    const prefetch = await fetchArticles(5);
    setArticles(fresh);
    setNextArticles(prefetch);
    setCurrentIndex(0);
    setRefreshing(false);
  }

  async function loadMoreArticles() {
    if (isFetchingMore) return;
    setIsFetchingMore(true);

    // Se non ci sono articoli prefetchati, eseguiamo un fetch al volo
    let articlesToAdd = nextArticles;
    if (articlesToAdd.length === 0) {
      articlesToAdd = await fetchArticles(5);
    }

    // Aggiungiamo gli articoli da mostrare al feed
    setArticles((prev) => [...prev, ...articlesToAdd]);

    // Pre-carichiamo nuovi articoli per le future chiamate
    const newArticles = await fetchArticles(5);
    setNextArticles(newArticles);
    setIsFetchingMore(false);
  }

  const onViewableItemsChanged = useRef(({ viewableItems }: any) => {
    if (!viewableItems || viewableItems.length === 0) return;
    const index = viewableItems[0].index;
    setCurrentIndex(index);
    const lastIndex = articles.length - 1;
    const prefetchTriggerIndex = lastIndex - 2;
    if (index === prefetchTriggerIndex) loadMoreArticles();
  }).current;

  const animatedRefreshStyle = {
    transform: [
      {
        translateY: scrollY.interpolate({
          inputRange: [-100, 0],
          outputRange: [60, -100],
          extrapolate: "clamp",
        }),
      },
    ],
    opacity: scrollY.interpolate({
      inputRange: [-100, -30],
      outputRange: [1, 0],
      extrapolate: "clamp",
    }),
  };

  if (loading) {
    return (
      <View style={styles.loaderContainer}>
        <ActivityIndicator size="large" />
      </View>
    );
  }

  return (
    <View style={{ flex: 1 }}>
      <Animated.View style={[styles.refreshContainer, animatedRefreshStyle]}>
        <ActivityIndicator size="small" color="#00ccff" />
      </Animated.View>

      <Animated.FlatList
        data={articles}
        renderItem={({ item, index }) => (
          <ArticleItem
            article={item}
            index={index}
            currentIndex={currentIndex}
            scrollY={scrollY}
            ITEM_HEIGHT={ITEM_HEIGHT}
            showCategory={showCategory}
            onAddCategory={onAddCategory}
          />
        )}
        keyExtractor={(item) => item.title}
        pagingEnabled
        snapToInterval={ITEM_HEIGHT}
        decelerationRate="fast"
        showsVerticalScrollIndicator={false}
        onViewableItemsChanged={onViewableItemsChanged}
        viewabilityConfig={viewabilityConfig}
        scrollEventThrottle={16}
        onScroll={Animated.event(
          [{ nativeEvent: { contentOffset: { y: scrollY } } }],
          {
            useNativeDriver: false,
            listener: (e: NativeSyntheticEvent<NativeScrollEvent>) => {
              pullDistance.current = e.nativeEvent.contentOffset.y;
            },
          }
        )}
        onScrollEndDrag={() => {
          if (
            pullDistance.current <= -70 &&
            currentIndex === 0 &&
            !refreshing
          ) {
            refreshArticles();
          }
        }}
        onEndReached={loadMoreArticles}
        onEndReachedThreshold={0.5}
        ListFooterComponent={() =>
          isFetchingMore ? (
            <View style={{ padding: 20 }}>
              <ActivityIndicator color="#fff" />
            </View>
          ) : null
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  loaderContainer: { flex: 1, justifyContent: "center", alignItems: "center" },
  refreshContainer: {
    position: "absolute",
    top: 0,
    left: 0,
    right: 0,
    zIndex: 10,
    alignItems: "center",
  },
});
