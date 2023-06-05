/* eslint-disable prefer-template */
/* eslint-disable react/jsx-curly-brace-presence */
/* eslint-disable import/newline-after-import */
/* eslint-disable prettier/prettier */
import MDBox from "components/MDBox";
import DashboardLayout from "examples/LayoutContainers/DashboardLayout";
import DashboardNavbar from "examples/Navbars/DashboardNavbar";
// import { Grid } from "@mui/material";
import Card from "@mui/material/Card";
// import { Link } from "react-router-dom";
import MDTypography from "components/MDTypography";
import MDButton from "components/MDButton";
// import Icon from "@mui/material/Icon";
import MDInput from "components/MDInput";
import { useState } from "react";
import axios from "axios";
import Swal from "sweetalert2";
export default function AddSession(){
    const [sessionInfo, setSessionInfo] = useState({
        name: ''
    });

    const handleChange = (e) => {
        setSessionInfo({
            ...sessionInfo,
            [e.target.name]: e.target.value
        })
    }
    console.log(sessionInfo.name)
    const sessionSubmit = (e) => {
        e.preventDefault();

        const data = new FormData();
        data.append('name',sessionInfo.name);

        axios.post('/add-session',data).then(response => {
            if(response){
                if(response.data.status === 200){
                    Swal.fire('Success',response.data.message,'success');
                }else if(response.data.status === 401){
                    Swal.fire('Error',response.data.message,'error');
                }
            }
        })
    }

    return (
        <DashboardLayout>
            <DashboardNavbar />

            

            <MDBox>
                <Card>
                    <MDBox px={5} py={5}>
                        <MDBox display="flex" justifyContent="space-between" alignItems="center">
                            <MDTypography variant="label" fontWeight="regular" style={{
                                width: 'max-content',
                                fontSize: 15 + 'px'
                            }}>Session Name</MDTypography>
                            <MDInput value={sessionInfo.name} onChange={handleChange} name="name" style={{width: 85 + '%'}} />
                        </MDBox>
                        <MDBox mt={5} display="flex" justifyContent="center" alignItems="center">
                        <MDButton onClick={sessionSubmit} variant="gradient" color="dark">
                            Submit
                        </MDButton>
                        </MDBox>
                    </MDBox>
                </Card>
            </MDBox>
      </DashboardLayout>
    )
}