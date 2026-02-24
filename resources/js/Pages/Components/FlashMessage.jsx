import React, { useState } from "react";
import { usePage } from '@inertiajs/react';

function FlashMessage() {
    const { flash } = usePage().props;

    const [alert_style, setShowAlert] = useState({});

    function hideAlert(){
        setShowAlert({display: "none"});
    }

    if (!flash?.success && !flash?.error) return null

    return (
        <div className="container mt-3">
            {flash.success && (
                <div className="alert alert-success alert-dismissible fade show" style={alert_style}>
                    {flash.success}
                    <button
                        type="button"
                        className="btn-close"
                        onClick={hideAlert}
                    />
                </div>
            )}

            {flash.error && (
                <div className="alert alert-danger alert-dismissible fade show" style={alert_style}>
                    {flash.error}
                    <button
                        type="button"
                        className="btn-close"
                        onClick={hideAlert}
                    />
                </div>
            )}
        </div>
    )
}

export default FlashMessage;