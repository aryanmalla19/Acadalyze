import { createContext, useEffect, useState } from "react";

export const AuthContext = createContext({
  authUser: null,
  setAuthUser: () => {},
});

export const AuthProvider = ({ children }) => {
  const [authUser, setAuthUser] = useState(null);

  useEffect(() => {
    console.log(authUser, "context.jsx");
  }, [authUser]);

  const values = {
    authUser,
    setAuthUser,
  };

  return <AuthContext.Provider value={values}>{children}</AuthContext.Provider>;
};
