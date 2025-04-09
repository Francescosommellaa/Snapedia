import React from "react";
import { StatusBar } from "expo-status-bar";
import MainNavigator from "./src/navigation/MainNavigator";
import { GestureHandlerRootView } from "react-native-gesture-handler";

export default function App() {
  return (
    <GestureHandlerRootView style={{ flex: 1 }}>
      <MainNavigator />
      <StatusBar style="auto" />
    </GestureHandlerRootView>
  );
}
