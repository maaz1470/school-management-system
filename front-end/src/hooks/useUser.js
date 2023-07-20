/* eslint-disable no-unused-expressions */
/* eslint-disable prefer-const */
/* eslint-disable import/no-mutable-exports */
/* eslint-disable import/newline-after-import */

import axios from "axios";
import { useEffect, useState } from "react";

/* eslint-disable prettier/prettier */

export default function useUser(){
    const [user, setUser] = useState(false);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        axios.get('/check-auth').then(response => {
            if(response.data.status === 200){
                setUser(true)
            }else if(response.data.status === 403){
                setUser(false)
            }
            setLoading(false)
        })
        return () => setUser(false)
    },[])
    if(loading){
        return undefined;
    }
    return user;
}