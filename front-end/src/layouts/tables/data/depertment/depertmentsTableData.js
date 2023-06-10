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

    const [sessions, setSessions] = useState([]);
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
      axios.get('/get-depertments').then(response => {
        console.log(response)
        if(response){
          if(response.data.status === 200){
            setSessions(response.data.depertments)
          }else if(response.data.status === 401){
            Swal.fire('Error',response.data.message,'error');
          }
          // setLoading(false)
        }
      });
      return () => setSessions([]);
    },[])
  
    const deleteSession = (e,id) => {
      e.preventDefault();
      
      axios.get(`/session-delete/${id}`).then(response => {
        if(response){
          if(response.data.status === 200){
            Swal.fire('Success', response.data.message,'success');
            e.target.closest('tr').remove()
          }else if(response.data.status === 404){
            Swal.fire('404',response.data.message,'error')
          }else if(response.data.status === 401){
            Swal.fire('Error',response.data.message,'error')
          }
        }
      })
    }
    let rows = {}
    if(loading){
      return <h1>Loading...</h1>
    }
  
    rows = sessions.map(el => {
      return ({
        name: <Title name={el.name} />,
        action: (
          <>
            <Link to={`/edit/${el.id}`}>
              <MDTypography component="span" variant="caption" color="text" fontWeight="medium" style={{
                marginRight: '5px',
                cursor: 'pointer'
              }}>Edit</MDTypography>
            </Link>
              
            <MDTypography onClick={(e) => deleteSession(e,el.id)} component="span" variant="caption" color="text" fontWeight="medium" style={{
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
  