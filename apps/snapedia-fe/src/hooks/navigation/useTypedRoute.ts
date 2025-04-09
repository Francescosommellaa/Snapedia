import { useRoute } from "@react-navigation/native";
import { RouteProp } from "@react-navigation/native";
import { RootStackParamList } from "../../../types/types";

export const useTypedRoute = () =>
  useRoute<RouteProp<RootStackParamList, "ArticleDetail">>();