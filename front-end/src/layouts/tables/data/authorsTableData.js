/* eslint-disable array-callback-return */
/* eslint-disable react/no-array-index-key */
/* eslint-disable prefer-const */
/* eslint-disable arrow-body-style */
/* eslint-disable prettier/prettier */
/* eslint-disable no-unused-vars */
/* eslint-disable react/prop-types */
/* eslint-disable react/function-component-definition */
/**
=========================================================
* Material Dashboard 2 React - v2.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard-react
* Copyright 2022 Creative Tim (https://www.creative-tim.com)

Coded by www.creative-tim.com

 =========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
*/

// Material Dashboard 2 React components
import MDBox from "components/MDBox";
import MDTypography from "components/MDTypography";
import MDAvatar from "components/MDAvatar";
// import MDBadge from "components/MDBadge";

// Images
import team2 from "assets/images/team-2.jpg";
import { useEffect, useState } from "react";
import axios from "axios";
import Swal from "sweetalert2";
// import team3 from "assets/images/team-3.jpg";
// import team4 from "assets/images/team-4.jpg";

export default function data() {

  const [sessions, setSessions] = useState([]);
  const [loading, setLoading] = useState(false)
  const Title = ({ name }) => (
    <MDBox display="flex" lineHeight={1}>
      {/* <MDAvatar src={image} name={name} size="sm" /> */}
      <MDBox ml={1} lineHeight={1}>
        <MDTypography display="block" variant="button" fontWeight="medium">
          {name}
        </MDTypography>
        {/* <MDTypography variant="caption">{email}</MDTypography> */}
      </MDBox>
    </MDBox>
  );

  useEffect(() => {
    axios.get('/get-sessions').then(response => {
      if(response){
        if(response.data.status === 200){
          setSessions(response.data.sessions)
        }else if(response.data.status === 401){
          Swal.fire('Error',response.data.message,'error');
        }
        // setLoading(false)
      }
    });
    return () => setSessions([]);
  },[])
  if(loading){
    return <h1>Loading...</h1>
  }

  const h = [
    {
      name: <Title name="Rahat Hossain" />,
      action: (
        <MDTypography component="a" href="#" variant="caption" color="text" fontWeight="medium">
          Edit
        </MDTypography>
      ),
    },
    {
      name: <Title name="Rahat Hossain" />,
      action: (
        <MDTypography component="a" href="#" variant="caption" color="text" fontWeight="medium">
          Edit
        </MDTypography>
      ),
    }
  ]


  return {
    columns: [
      { Header: "Name", accessor: "name", width: "45%", align: "left" },
      { Header: "Action", accessor: "action", align: "center" },
    ],

    
  };
}
