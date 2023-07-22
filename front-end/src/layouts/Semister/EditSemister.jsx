/* eslint-disable no-console */
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

export default function EditSemister(){

    const [semister, setSemister] = useState({
        name: ''
    })

    const [loading, setLoading] = useState(true);

    const handleChange = (e) => {
        e.preventDefault();
        setSemister({
            ...semister,
            [e.target.name]: e.target.value
        })
    }

    const {id} = useParams();


    useEffect(() => {
        axios.get(`/semister/get-data/${id}`).then(response => {
            if(response){
                if(response.data.status === 200){
                    setSemister({
                        ...semister,
                        name: response.data.semister.name ?? ''
                    })
                    setLoading(false)
                }else if(response.data.status === 500){
                    Swal.fire('Error',response.data.message,'error');
                }else{
                    Swal.fire('Error','Something went wrong. Please try again.');
                }
            }
        });
    },[id]);

    const semisterSubmit = (e) => {
        e.preventDefault();
        const data = new FormData();

        data.append('name',semister.name);
        data.append('id',id);

        axios.post('/semister/update-semister',data).then(response => {
            if(response){
                if(response.data.status === 200){
                    Swal.fire('Success',response.data.message,'success')
                }else if(response.data.status === 401){
                    Swal.fire('Error',response.data.message,'error');
                }else{
                    Swal.fire('Error','Something went wrong. Please contact your developer.','error');
                }
            }
        });

    }

    if(loading){
        return '';
    }



    return (
        <DashboardLayout>
            <DashboardNavbar />

            <MDBox>
                <Card>
                    <form action="" onSubmit={semisterSubmit}>
                        <MDBox px={5} py={5}>
                            <MDBox display="flex" justifyContent="space-between" alignItems="center">
                                <MDTypography variant="label" fontWeight="regular" style={{
                                    width: 'max-content',
                                    fontSize: 15 + 'px'
                                }}>Name</MDTypography>
                                <MDInput style={{width: 85 + '%'}} name="name" onChange={handleChange} value={semister.name} />
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