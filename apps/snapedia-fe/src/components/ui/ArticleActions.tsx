import React from "react";
import { View, StyleSheet } from "react-native";
import Icon from "./Icon";

interface Props {
  liked?: boolean;
  saved?: boolean;
  onLikePress?: () => void;
  onSavePress?: () => void;
  onCommentPress?: () => void;
  onSharePress?: () => void;
}

const ArticleActions: React.FC<Props> = ({
  liked,
  saved,
  onLikePress,
  onSavePress,
  onCommentPress,
  onSharePress,
}) => {
  return (
    <View style={styles.actions}>
      <Icon name="like" initiallyActive={liked} onPress={onLikePress} />
      <Icon name="comment" onPress={onCommentPress} />
      <Icon name="share" onPress={onSharePress} />
      <Icon name="save" initiallyActive={saved} onPress={onSavePress} />
    </View>
  );
};

const styles = StyleSheet.create({
  actions: {
    flexDirection: "column",
    alignItems: "flex-end",
    marginTop: 20,
    gap: 18,
  },
});

export default ArticleActions;
