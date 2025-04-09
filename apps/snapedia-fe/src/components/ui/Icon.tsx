import React, { useState } from "react";
import { TouchableOpacity, StyleProp, ViewStyle } from "react-native";

// Importa tutte le icone da assets
import CommentIcon from "../../assets/icons/comment.svg";
import CommentActiveIcon from "../../assets/icons/comment-active.svg";
import EditIcon from "../../assets/icons/edit.svg";
import EditActiveIcon from "../../assets/icons/edit-active.svg";
import ExpandIcon from "../../assets/icons/expand.svg";
import ExpandActiveIcon from "../../assets/icons/expand-active.svg";
import LikeIcon from "../../assets/icons/like.svg";
import LikeActiveIcon from "../../assets/icons/like-active.svg";
import PlusIcon from "../../assets/icons/plus.svg";
import PlusActiveIcon from "../../assets/icons/plus-active.svg";
import SaveIcon from "../../assets/icons/save.svg";
import SaveActiveIcon from "../../assets/icons/save-active.svg";
import SettingsIcon from "../../assets/icons/settings.svg";
import SettingsActiveIcon from "../../assets/icons/settings-active.svg";
import ShareIcon from "../../assets/icons/share.svg";

// Organizza icone in coppie (active/inactive)
const icons = {
  comment: { inactive: CommentIcon, active: CommentActiveIcon },
  edit: { inactive: EditIcon, active: EditActiveIcon },
  expand: { inactive: ExpandIcon, active: ExpandActiveIcon },
  like: { inactive: LikeIcon, active: LikeActiveIcon },
  plus: { inactive: PlusIcon, active: PlusActiveIcon },
  save: { inactive: SaveIcon, active: SaveActiveIcon },
  settings: { inactive: SettingsIcon, active: SettingsActiveIcon },
  share: { inactive: ShareIcon, active: ShareIcon },
};

interface IconProps {
  name: keyof typeof icons;
  size?: number;
  style?: StyleProp<ViewStyle>;
  initiallyActive?: boolean;
  onPress?: (isActive: boolean) => void;
}

const Icon: React.FC<IconProps> = ({
  name,
  size = 28,
  style,
  initiallyActive = false,
  onPress,
}) => {
  const [active, setActive] = useState(initiallyActive);

  // Se c'è una sola icona (no active), rimane invariata
  const IconSvg =
    active && icons[name].active !== icons[name].inactive
      ? icons[name].active
      : icons[name].inactive;

  const handlePress = () => {
    // cambia stato solo se l'icona active esiste
    if (icons[name].active && icons[name].inactive !== icons[name].active) {
      const newActive = !active;
      setActive(newActive);
      if (onPress) onPress(newActive);
    } else if (onPress) {
      onPress(active);
    }
  };

  return (
    <TouchableOpacity onPress={handlePress} style={style}>
      <IconSvg width={size} height={size} />
    </TouchableOpacity>
  );
};

export default Icon;
