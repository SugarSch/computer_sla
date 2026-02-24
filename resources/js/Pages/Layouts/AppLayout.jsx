import React from "react";
import FlashMessage from '../Components/FlashMessage';
import Navbar from '../Components/Navbar';

function AppLayout({ children }) {
    return (
        <>
            <Navbar />
            <FlashMessage />
            {children}
        </>
    )
}


export default AppLayout;
