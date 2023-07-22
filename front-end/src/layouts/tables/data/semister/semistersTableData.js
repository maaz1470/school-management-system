/* eslint-disable no-console */
/* eslint-disable arrow-body-style */
/* eslint-disable react/prop-types */
/* eslint-disable react/function-component-definition */
/* eslint-disable prettier/prettier */
import MDBox from "components/MDBox";
import MDTypography from "components/MDTypography";
import { useEffect, useState } from "react";
import axios from "axios";
import Swal from "sweetalert2";
import { Link } from "react-router-dom";

export default function data() {

    const [semister, setSemister] = useState([]);
    // eslint-disable-next-line no-unused-vars
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
      axios.get('/semister/get-all').then(response => {
        if(response){
          if(response.data.status === 200){
            setSemister(response.data.semisters)
          }else if(response.data.status === 401){
            Swal.fire('Error',response.data.message,'error');
          }
          // setLoading(false)
        }
      });
      return () => setSemister([]);
    },[])
 
  
    const deleteSemister = (e,id) => {
      e.preventDefault();
      
      axios.get(`/semister/delete/${id}`).then(response => {
        if(response){
          if(response.data.status === 200){
            Swal.fire('Success', response.data.message,'success');
            e.target.closest('tr').remove()
          }else if(response.data.status === 404){
            Swal.fire('404',response.data.message,'error')
          }else if(response.data.status === 401){
            Swal.fire('Error',response.data.message,'error')
          }else if(response.data.status === 500){
            Swal.fire('Error',response.data.message,'error')
          }else{
            Swal.fire('Error','Soemthing went wrong. Please try again.','error')
          }
        }
      })
    }
    let rows = {}
    if(loading){
      return <h1>Loading...</h1>
    }
  
    rows = semister.map(el => {
      return ({
        name: <Title name={el.name} />,
        action: (
          <>
            <Link to={`/semister/edit/${el.id}`}>
              <MDTypography component="span" variant="caption" color="text" fontWeight="medium" style={{
                marginRight: '5px',
                cursor: 'pointer'
              }}>Edit</MDTypography>
            </Link>
              
            <MDTypography onClick={(e) => deleteSemister(e,el.id)} component="span" variant="caption" color="text" fontWeight="medium" style={{
              cursor: 'pointer'
            }}>
              Delete
            </MDTypography>
          </>
        ),
      })
    })
    
  
  
    return {
      columns: [
        { Header: "Name", accessor: "name", width: "45%", align: "left" },
        { Header: "Action", accessor: "action", align: "center" },
      ],
  
      rows
    };
  }
  