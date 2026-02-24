import React from "react";
import { Link, router, usePage } from '@inertiajs/react';

function Navbar() {
  const { auth } = usePage().props

  // hide navbar if not logged in
  if (!auth?.user) return null

  const logout = () => {
    router.post('/logout')
  }
  
  return (
    <nav className="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
      <div className="container">
        {/* App name */}
        <Link className="navbar-brand fw-semibold" href="/">
          Computer SLA
        </Link>

        {/* Mobile toggle */}
        <button
          className="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarContent"
        >
          <span className="navbar-toggler-icon"></span>
        </button>

        <div className="collapse navbar-collapse" id="navbarContent">
          <ul className="navbar-nav ms-auto align-items-lg-center gap-2">
            <li className="nav-item text-white">
              👤 {auth.user.username}
            </li>
            <li className="nav-item">
              <button
                onClick={logout}
                className="btn btn-sm btn-outline-light"
              >
                ออกจากระบบ
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  )
}

export default Navbar;
