import React from "react";
import { createBottomTabNavigator } from "@react-navigation/bottom-tabs";
import { enableScreens } from "react-native-screens";
import { Dimensions, View } from "react-native";

import FeedScreen from "../components/screens/main/feed/FeedSwitcherScreen";
import SearchScreen from "../components/screens/main/SearchScreen";
import PremiumScreen from "../components/screens/main/PremiumScreen";
import ProfileScreen from "../components/screens/main/Profile/ProfileScreen";

import FeedIcon from "../assets/icons/feed.svg";
import FeedActiveIcon from "../assets/icons/feed-active.svg";
import SearchIcon from "../assets/icons/search.svg";
import SearchActiveIcon from "../assets/icons/search-active.svg";
import PremiumIcon from "../assets/icons/star.svg";
import PremiumActiveIcon from "../assets/icons/star-active.svg";
import ProfileIcon from "../assets/icons/profile.svg";
import ProfileActiveIcon from "../assets/icons/profile-active.svg";

enableScreens();
const Tab = createBottomTabNavigator();

const icons = {
  Feed: { default: FeedIcon, active: FeedActiveIcon },
  Search: { default: SearchIcon, active: SearchActiveIcon },
  Premium: { default: PremiumIcon, active: PremiumActiveIcon },
  Profile: { default: ProfileIcon, active: ProfileActiveIcon },
} as const;

const { width } = Dimensions.get("window");

function TabNavigator() {
  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        headerShown: false,
        tabBarShowLabel: false,
        tabBarStyle: {
          position: "absolute",
          backgroundColor: "rgba(0,0,0,0.4)",
          borderTopWidth: 0,
          elevation: 30,
          bottom: 24,
          marginHorizontal: width * 0.05,
          height: 64,
          width: width * 0.9,
          borderRadius: 40,
          overflow: "hidden",
        },
        tabBarIcon: ({ focused }) => {
          const iconSet = icons[route.name as keyof typeof icons];
          const IconComponent = focused ? iconSet.active : iconSet.default;
          return (
            <View
              style={{
                marginTop: 18,
              }}
            >
              <IconComponent width={28} height={28} />
            </View>
          );
        },
      })}
    >
      <Tab.Screen name="Feed" component={FeedScreen} />
      <Tab.Screen name="Search" component={SearchScreen} />
      <Tab.Screen name="Premium" component={PremiumScreen} />
      <Tab.Screen name="Profile" component={ProfileScreen} />
    </Tab.Navigator>
  );
}
export default TabNavigator;
