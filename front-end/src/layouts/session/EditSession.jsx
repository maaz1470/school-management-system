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
import { useEffect, useState } from "react";
import axios from "axios";
import Swal from "sweetalert2";
import { useNavigate, useParams } from "react-router-dom";
export default function EditSession(){
    const [sessionInfo, setSessionInfo] = useState({
        name: ''
    });

    const [loading, setLoading] = useState(true);

    const navigate = useNavigate();

    const {id} = useParams();
    useEffect(() => {
        axios.get(`/edit-session/${id}`).then(response => {
            if(response){
                if(response.data.status === 200){
                    setSessionInfo({
                        ...sessionInfo,
                        name: response.data.session.name ?? ''
                    })
                }else if(response.data.status === 404){
                    Swal.fire('404',response.data.message,'error');
                    navigate('/session',{
                        replace: true
                    })

                }
                // console.log(response)
            }
            setLoading(false)
        })

        return () => setSessionInfo({
            name: ''
        })
    },[])
    const handleChange = (e) => {
        setSessionInfo({
            ...sessionInfo,
            [e.target.name]: e.target.value
        })
    }

    

    const sessionSubmit = (e) => {
        e.preventDefault();

        const data = new FormData();
        data.append('name',sessionInfo.name);
        data.append('id',id);

        axios.post('/update-session',data).then(response => {
            if(response){
                if(response.data.status === 200){
                    Swal.fire('Success',response.data.message,'success');
                }else if(response.data.status === 401){
                    Swal.fire('Error',response.data.message,'error');
                }else if(response.data.status === 404){
                    Swal.fire('404',response.data.message,'error');
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