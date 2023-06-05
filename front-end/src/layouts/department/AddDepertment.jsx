/* eslint-disable prefer-template */
/* eslint-disable react/jsx-curly-brace-presence */
/* eslint-disable import/newline-after-import */
/* eslint-disable prettier/prettier */
import MDBox from "components/MDBox";
import DashboardLayout from "examples/LayoutContainers/DashboardLayout";
import DashboardNavbar from "examples/Navbars/DashboardNavbar";
import { Grid } from "@mui/material";
import Card from "@mui/material/Card";
import { Link } from "react-router-dom";
import MDTypography from "components/MDTypography";
import MDButton from "components/MDButton";
import Icon from "@mui/material/Icon";
import MDInput from "components/MDInput";
export default function AddDepertment(){
    return (
        <DashboardLayout>
            <DashboardNavbar />

            <MDBox pt={6} pb={3}>
                <Grid container spacing={6}>
                    <Grid item xs={12}>
                        <Card>
                        <MDBox
                            mx={2}
                            mt={-3}
                            py={3}
                            px={2}
                            variant="gradient"
                            bgColor="info"
                            borderRadius="lg"
                            coloredShadow="info"
                        >
                            <MDBox px={2} display="flex" justifyContent="space-between" alignItems="center">
                                <MDTypography variant="h6" color="white">
                                Add Depertment
                                </MDTypography>
                                <Link to={'/add-depertment'}>
                                    <MDButton variant="gradient" color="dark">
                                        <Icon sx={{ fontWeight: "bold" }}>add</Icon>
                                        &nbsp;add new Depertment
                                    </MDButton>
                                </Link>
                            </MDBox>
                        </MDBox>
                        
                        
                        </Card>
                        
                    </Grid>
                </Grid>
            </MDBox>
            <MDBox>
                <Card>
                    <MDBox px={5} py={5}>
                        <MDBox display="flex" justifyContent="space-between" alignItems="center">
                            <MDTypography variant="label" fontWeight="normal" style={{
                                width: 'max-content',
                                fontSize: 15 + 'px'
                            }}>Name</MDTypography>
                            <MDInput style={{width: 85 + '%'}} />
                        </MDBox>
                        <MDBox mt={5} display="flex" justifyContent="center" alignItems="center">
                        <MDButton variant="gradient" color="dark">
                            Submit
                        </MDButton>
                        </MDBox>
                    </MDBox>
                </Card>
            </MDBox>
      </DashboardLayout>
    )
}