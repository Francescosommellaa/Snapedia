import React from "react";
import {
  View,
  Text,
  StyleSheet,
  Image,
  ScrollView,
  TouchableOpacity,
} from "react-native";
import { useSafeAreaInsets } from "react-native-safe-area-context";

import { THEME } from "../../../../../constants/theme";

// Icon
import Icon from "../../../ui/Icon";

// Mock categorie (colore da definire tu)
const categories = [
  { label: "SPORT", color: "#A14A3A" },
  { label: "STORIA", color: "#3A6DA1" },
  { label: "SCIENZA", color: "#2E9C99" },
  { label: "ARTE E CULTURA", color: "#7C4DAD" },
  { label: "TECNOLOGIA", color: "#3A7A49" },
];

export default function ProfileScreen() {
  const insets = useSafeAreaInsets();

  return (
    <View style={[styles.container, { paddingTop: insets.top }]}>
      <ScrollView contentContainerStyle={styles.content}>
        {/* 🔝 Header */}
        <View style={styles.header}>
          <Text style={styles.headerTitle}>Profilo</Text>
          <View style={styles.headerIcons}>
            <TouchableOpacity style={styles.iconButton}>
              <Icon name="edit" size={28} />
            </TouchableOpacity>
            <TouchableOpacity style={styles.iconButton}>
              <Icon name="settings" size={28} />
            </TouchableOpacity>
          </View>
        </View>

        {/* 🖼️ Foto + nome */}
        <View style={styles.profileSection}>
          <View style={styles.avatar}>
            <Text style={styles.avatarInitial}>F</Text>
          </View>
          <Text style={styles.fullName}>Francesco Sommella</Text>
        </View>

        {/* 🏷️ Categorie */}
        <View style={styles.categoriesSection}>
          <Text style={styles.sectionLabel}>CATEGORIE</Text>
        </View>
        <View style={styles.categoriesList}>
          {categories.map((cat) => (
            <View
              key={cat.label}
              style={[styles.categoryTag, { backgroundColor: cat.color }]}
            >
              <Text style={styles.categoryText}>{cat.label}</Text>
            </View>
          ))}
        </View>

        {/* 🔐 Info di accesso */}
        <Text style={styles.sectionTitle}>INFO DI ACCESSO</Text>
        <View style={styles.infoBlock}>
          <Text style={styles.infoLabel}>NOME UTENTE</Text>
          <Text style={styles.infoText}>Francescosommellaa</Text>
        </View>
        <View style={styles.infoBlock}>
          <Text style={styles.infoLabel}>EMAIL PRINCIPALE</Text>
          <Text style={styles.infoText}>Francescosommellaa@gmail.com</Text>
        </View>

        {/* 📇 Info personali */}
        <Text style={styles.sectionTitle}>INFO PERSONALI</Text>
        <View style={styles.infoBlock}>
          <Text style={styles.infoLabel}>NUMERO DI TELEFONO</Text>
          <Text style={styles.infoText}>+39 | 3773711446</Text>
        </View>
        <View style={styles.infoBlock}>
          <Text style={styles.infoLabel}>EMAIL SECONDARIA</Text>
          <Text style={styles.infoText}>Franc.sommella@gmail.com</Text>
        </View>
        <View style={styles.infoBlock}>
          <Text style={styles.infoLabel}>INDIRIZZO</Text>
          <Text style={styles.infoText}>
            Via Carlo Poerio 25{"\n"}
            Quarto Napoli 80010{"\n"}
            Campania Italia
          </Text>
        </View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#000",
  },
  content: {
    paddingHorizontal: 20,
    paddingBottom: 40,
  },
  header: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 24,
  },
  headerTitle: {
    color: "#fff",
    fontSize: 24,
    fontWeight: "bold",
  },
  headerIcons: {
    flexDirection: "row",
    gap: 16,
  },
  iconButton: {
    padding: 4,
  },
  profileSection: {
    flexDirection: "row",
    alignItems: "center",
    gap: 12,
    marginBottom: 24,
  },
  avatar: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: "#999",
    justifyContent: "center",
    alignItems: "center",
  },
  avatarInitial: {
    fontSize: 32,
    color: "#000",
    fontWeight: "bold",
  },
  fullName: {
    color: "#fff",
    fontSize: 22,
    fontWeight: "bold",
  },
  categoriesSection: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 12,
  },
  sectionLabel: {
    color: "#888",
    fontWeight: "600",
  },
  categoriesList: {
    flexDirection: "row",
    flexWrap: "wrap",
    gap: 8,
    marginBottom: 32,
  },
  categoryTag: {
    paddingVertical: 6,
    paddingHorizontal: 12,
    borderRadius: 20,
  },
  categoryText: {
    color: "#fff",
    fontWeight: "600",
    fontSize: 12,
  },
  sectionTitle: {
    color: "#999",
    fontWeight: "bold",
    fontSize: 20,
    marginBottom: 12,
  },
  infoBlock: {
    marginBottom: 16,
  },
  infoLabel: {
    color: "#888",
    fontWeight: "600",
    fontSize: 12,
    marginBottom: 4,
  },
  infoText: {
    color: "#fff",
    fontWeight: "bold",
    fontSize: 14,
  },
});
