import { createContext, useState } from "react";

export const AuthContext = createContext({
  authUser: null,
  setAuthUser: () => {},
});

export const AuthProvider = ({ children }) => {
  const [authUser, setAuthUser] = useState(null);

  const values = {
    authUser,
    setAuthUser,
  };

  return <AuthContext.Provider value={values}>{children}</AuthContext.Provider>;
};
