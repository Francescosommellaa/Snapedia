import React from "react";
import { View, Text, StyleSheet } from "react-native";
import { THEME } from "../../../../constants/theme";

export default function SearchScreen() {
  return (
    <View style={styles.container}>
      <Text style={styles.text}>Io sono Ricerca</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, alignItems: "center", justifyContent: "center" },
  text: { fontSize: 20 },
});
