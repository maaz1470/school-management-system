/* eslint-disable react/jsx-curly-brace-presence */
/* eslint-disable import/no-duplicates */
/* eslint-disable prefer-template */
/* eslint-disable react/jsx-no-useless-fragment */
import { Link } from "react-router-dom";
import MDBox from "components/MDBox";
import DashboardLayout from "examples/LayoutContainers/DashboardLayout";
import DashboardNavbar from "examples/Navbars/DashboardNavbar";
import { Grid } from "@mui/material";

import Card from "@mui/material/Card";
import MDTypography from "components/MDTypography";
import DataTable from "examples/Tables/DataTable";
import authorsTableData from "layouts/tables/data/authorsTableData";
import MDButton from "components/MDButton";
import Icon from "@mui/material/Icon";
// import projectsTableData from "layouts/tables/data/projectsTableData";
/* eslint-disable prettier/prettier */
export default function Depertment(){
    const { columns, rows } = authorsTableData();
//   const { columns: pColumns, rows: pRows } = projectsTableData();
    return (
        <>
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
                                    Depertment Table
                                    </MDTypography>
                                    <Link to={'/add-depertment'}>
                                        <MDButton variant="gradient" color="dark">
                                            <Icon sx={{ fontWeight: "bold" }}>add</Icon>
                                            &nbsp;add new Depertment
                                        </MDButton>
                                    </Link>
                                </MDBox>
                            
                            </MDBox>
                            <MDBox pt={3}>
                                <DataTable
                                table={{ columns, rows }}
                                isSorted={false}
                                entriesPerPage={false}
                                showTotalEntries={false}
                                noEndBorder
                                />
                            </MDBox>
                            </Card>
                        </Grid>
                    </Grid>
                </MDBox>
            </DashboardLayout>
        </>
    )
}