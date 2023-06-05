/* eslint-disable prefer-arrow-callback */
/* eslint-disable prefer-const */
/* eslint-disable import/order */
/* eslint-disable import/newline-after-import */
/* eslint-disable import/no-useless-path-segments */
/* eslint-disable no-unused-vars */
/* eslint-disable react/jsx-curly-brace-presence */
/* eslint-disable react/prop-types */
/* eslint-disable prettier/prettier */
import axios from "axios";
import { useEffect, useState } from "react";
import { Navigate, Outlet, Routes, Route } from "react-router-dom";
import Signup from './../layouts/authentication/sign-up'
import Swal from "sweetalert2";
export default function PrivateRoutes(){
    const [user, setUser] = useState(false);
    const [loading, setLoading] = useState(true);
    useEffect(() => {
        axios.get('/check-auth').then(response => {
            if(response){
                if(response.data.status === 200){
                    setUser(true);
                }else if(response.data.status === 403){
                    Swal.fire('Error',response.data.message,'error');
                    setUser(false);
                }else{
                    Swal.fire('Error',response.data.message,'error');
                }
                
                setLoading(false)
            }
        })
        return () => setUser(false)
    },[])



    if(loading){
        return <h1>Loading...</h1>;
    }

    return user ? <Outlet /> : <Navigate to="/authentication/sign-in" />;
}