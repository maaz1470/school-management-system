/* eslint-disable no-else-return */
/* eslint-disable no-unused-vars */
/* eslint-disable prefer-template */
/* eslint-disable react/jsx-curly-brace-presence */
/* eslint-disable import/newline-after-import */
/* eslint-disable prettier/prettier */
import MDBox from "components/MDBox";
import DashboardLayout from "examples/LayoutContainers/DashboardLayout";
import DashboardNavbar from "examples/Navbars/DashboardNavbar";
import {  InputLabel, MenuItem, Select } from "@mui/material";
import Card from "@mui/material/Card";
// import { Link } from "react-router-dom";
import MDTypography from "components/MDTypography";
import MDButton from "components/MDButton";
// import Icon from "@mui/material/Icon";
import MDInput from "components/MDInput";
import FormControl from "@mui/material/FormControl";
import { useEffect, useState } from "react";
import axios from "axios";
import Swal from "sweetalert2";
import { useParams } from "react-router-dom";

export default function AddDepertment(){

    const [depertment, setDepertment] = useState({
        name: '',
        session: ''
    });

    const [session, setSession] = useState({
        id: '',
        name: ''
    })

    const [loading, setLoading] = useState(true);


    const handleChange = (e) => {
        e.preventDefault();
        setDepertment({
            ...depertment,
            [e.target.name]: e.target.value
        })
    }

    const {id} = useParams();

    useEffect(() => {
        axios.get('/get-sessions').then(response => {
            if(response){
                if(response.data.status === 200){
                    setSession(response.data.sessions)
                }else{
                    Swal.fire('Error', 'Session not found','error')
                }
                setLoading(false)
            }
        })

        return () => setSession([])
    },[])

    useEffect(() => {
        axios.get(`/depertment/edit/${id}`).then(response => {
            if(response){
                if(response.data.status === 200){
                    setDepertment({
                        ...depertment,
                        name: response.data.depertment.name ?? '',
                        session: response.data.depertment.session ?? ''
                    })
                }
            }
        });

        return () => setDepertment({
            name: '',
            session: ''
        })
    },[id])

    const depertmentSubmit = (e) => {
        e.preventDefault();
        const data = new FormData();
        data.append('name',depertment.name)
        data.append('session',depertment.session)
        data.append('id',id)
        axios.post('/update-depertment',data).then(response => {
            if(response){
                if(response.data.status === 200){
                    Swal.fire('Success',response.data.message,'success');
                }else if(response.data.status === 401){
                    Swal.fire('Error',response.data.message,'error');
                }else{
                    Swal.fire('Error','Something went wrong. Please try again.','error');
                }
            }
        })
    }

    if(loading){
        return <h1>Loading...</h1>
    }


    return (
        <DashboardLayout>
            <DashboardNavbar />

            <MDBox>
                <Card>
                    <form action="" onSubmit={depertmentSubmit}>
                        <MDBox px={5} py={5}>
                            <MDBox display="flex" justifyContent="space-between" alignItems="center">
                                <MDTypography variant="label" fontWeight="regular" style={{
                                    width: 'max-content',
                                    fontSize: 15 + 'px'
                                }}>Name</MDTypography>
                                <MDInput style={{width: 85 + '%'}} name="name" onChange={handleChange} value={depertment.name} />
                            </MDBox>
                            <MDBox display="flex" justifyContent="space-between" alignItems="center" style={{
                                marginTop: '10px'
                            }}>
                                <MDTypography variant="label" fontWeight="regular" style={{
                                    width: 'max-content',
                                    fontSize: 15 + 'px'
                                }}>Session</MDTypography>
                                
                                    <FormControl fullWidth style={{ width: '85%' }} size="small">
                                        <InputLabel id="session">Session</InputLabel>
                                        <Select onChange={handleChange} value={depertment.session} name="session" labelId="session" style={{height: '40px'}} id="session_id">
                                            {
                                                session.map(el => (
                                                    <MenuItem value={el.id} key={el.id}>{el.name}</MenuItem>
                                                ))
                                            }
                                        </Select>
                                    </FormControl>
                            </MDBox>
                            <MDBox mt={5} display="flex" justifyContent="center" alignItems="center">
                            <MDButton variant="gradient" color="dark" type="submit">
                                Submit
                            </MDButton>
                            </MDBox>
                        </MDBox>
                    </form>
                </Card>
            </MDBox>
      </DashboardLayout>
    )
}