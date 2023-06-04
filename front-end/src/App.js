/* eslint-disable react/jsx-no-useless-fragment */
/* eslint-disable import/order */
/* eslint-disable import/newline-after-import */
/* eslint-disable prettier/prettier */
/* eslint-disable import/no-unresolved */
import Main from "Main";
import { Navigate, Route, Routes } from "react-router-dom";
import Signin from './layouts/authentication/sign-in'
import Signup from './layouts/authentication/sign-up'
import routes from "routes";
import PrivateRoutes from "routes/PrivateRoutes";
import useUser from "hooks/useUser";
export default function App(){
  return (
    <Routes>

          <Route path="/" element={<Main />}>
            <Route path="/" element={<Navigate to="/authentication/sign-in" />} />
            <Route path="/" element={<PrivateRoutes />}>
              {routes.map((el) => <Route exact path={el.route} element={el.component} key={el.key} />)}
            </Route>
            {useUser() ? (
            <>
              <Route path="/authentication/sign-in" element={<Navigate to="/dashboard" />} />
              <Route path="/authentication/sign-up" element={<Navigate to="/dashboard" />} />
            </>) : (
              <>
                <Route path="/authentication/sign-in" element={<Signin />} />
                <Route path="/authentication/sign-up" element={<Signup />} />
              </>
            )}
          </Route>
        <Route path="*" element={<Navigate to="/authentication/sign-in" />} />
        
    </Routes>
  )
}