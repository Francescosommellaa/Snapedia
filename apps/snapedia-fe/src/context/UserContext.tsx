import React, { createContext, useReducer, useContext, ReactNode } from "react";

// 1️⃣ Definisci il tipo di stato utente
interface UserState {
  isAuthenticated: boolean;
  name: string;
  email: string;
  avatar?: string;
}

// 2️⃣ Stato iniziale
const initialState: UserState = {
  isAuthenticated: false,
  name: "",
  email: "",
  avatar: undefined,
};

// 3️⃣ Azioni disponibili
type Action =
  | { type: "LOGIN"; payload: { name: string; email: string; avatar?: string } }
  | { type: "LOGOUT" }
  | { type: "UPDATE_PROFILE"; payload: Partial<UserState> };

// 4️⃣ Reducer: aggiorna lo stato in base all’azione
const userReducer = (state: UserState, action: Action): UserState => {
  switch (action.type) {
    case "LOGIN":
      return { ...state, ...action.payload, isAuthenticated: true };
    case "LOGOUT":
      return { ...initialState };
    case "UPDATE_PROFILE":
      return { ...state, ...action.payload };
    default:
      return state;
  }
};

// 5️⃣ Crea il context
const UserContext = createContext<{
  state: UserState;
  dispatch: React.Dispatch<Action>;
}>({
  state: initialState,
  dispatch: () => null,
});

// 6️⃣ Provider da avvolgere attorno all’intera app
export const UserProvider = ({ children }: { children: ReactNode }) => {
  const [state, dispatch] = useReducer(userReducer, initialState);

  return (
    <UserContext.Provider value={{ state, dispatch }}>
      {children}
    </UserContext.Provider>
  );
};

// 7️⃣ Custom hook per usare facilmente il context
export const useUser = () => useContext(UserContext);
