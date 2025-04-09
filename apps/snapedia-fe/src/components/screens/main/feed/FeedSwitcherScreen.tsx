import React, { useState, useEffect } from "react";
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  Animated,
} from "react-native";
import { PanGestureHandler, State } from "react-native-gesture-handler";
import { useSafeAreaInsets } from "react-native-safe-area-context";
import ForYouScreen from "./ForYouScreen";
import ExploreScreen from "./ExploreScreen";
import { fetchMultipleArticles } from "../../../../../services/wikipediaApi";

async function fetchPopularArticles(limit: number) {
  return fetchMultipleArticles(limit, "it");
}

async function fetchArticlesForUser(limit: number) {
  return fetchMultipleArticles(limit, "it");
}

export default function FeedSwitcherScreen() {
  const insets = useSafeAreaInsets();
  const [selectedTab, setSelectedTab] = useState<"forYou" | "explore">(
    "forYou"
  );
  const [exploreInitial, setExploreInitial] = useState<any[]>([]);
  const [forYouInitial, setForYouInitial] = useState<any[]>([]);

  useEffect(() => {
    fetchPopularArticles(5).then((articles) => {
      setExploreInitial(articles);
    });
    fetchArticlesForUser(5).then((articles) => {
      setForYouInitial(articles);
    });
  }, []);

  // Animated value per tracciare lo swipe
  const translateX = new Animated.Value(0);
  const SWIPE_THRESHOLD = 50; // soglia in pixel

  const onGestureEvent = Animated.event(
    [{ nativeEvent: { translationX: translateX } }],
    { useNativeDriver: true }
  );

  const onHandlerStateChange = (event: any) => {
    if (event.nativeEvent.oldState === State.ACTIVE) {
      const { translationX } = event.nativeEvent;
      // Se l'utente swipea verso sinistra (translationX negativo) e supera la soglia, attiva "Esplora"
      if (translationX < -SWIPE_THRESHOLD) {
        setSelectedTab("explore");
      }
      // Se l'utente swipea verso destra (translationX positivo) e supera la soglia, attiva "Per Te"
      else if (translationX > SWIPE_THRESHOLD) {
        setSelectedTab("forYou");
      }
      // Annulla la traduzione (animazione di reset)
      Animated.timing(translateX, {
        toValue: 0,
        duration: 200,
        useNativeDriver: true,
      }).start();
    }
  };

  return (
    <PanGestureHandler
      onGestureEvent={onGestureEvent}
      onHandlerStateChange={onHandlerStateChange}
    >
      <Animated.View style={{ flex: 1 }}>
        <View style={[styles.headerContainer, { paddingTop: insets.top + 10 }]}>
          <TouchableOpacity onPress={() => setSelectedTab("forYou")}>
            <Text
              style={[
                styles.tabText,
                selectedTab === "forYou" && styles.activeTab,
              ]}
            >
              Per Te
            </Text>
          </TouchableOpacity>
          <Text style={[styles.divider]}>l</Text>
          <TouchableOpacity onPress={() => setSelectedTab("explore")}>
            <Text
              style={[
                styles.tabText,
                selectedTab === "explore" && styles.activeTab,
              ]}
            >
              Esplora
            </Text>
          </TouchableOpacity>
        </View>
        {selectedTab === "forYou" ? (
          <ForYouScreen initialArticles={forYouInitial} />
        ) : (
          <ExploreScreen initialArticles={exploreInitial} />
        )}
      </Animated.View>
    </PanGestureHandler>
  );
}

const styles = StyleSheet.create({
  headerContainer: {
    position: "absolute",
    top: 0,
    left: 0,
    right: 0,
    zIndex: 20,
    flexDirection: "row",
    justifyContent: "center",
    alignItems: "center",
    paddingVertical: 8,
  },
  divider: {
    fontSize: 24,
    color: "#fff",
    opacity: 0.6,
  },
  tabText: {
    marginHorizontal: 12,
    fontSize: 20,
    color: "#fff",
    opacity: 0.6,
  },
  activeTab: {
    opacity: 1,
  },
});
