import React from "react";

function Home({ title }) {
  return (
    <div className="container min-vh-100 d-flex align-items-center justify-content-center">
      <div className="row w-100 justify-content-center">
        <div className="col-12 col-md-10 col-lg-8 text-center">

          <h1 className="fw-semibold mb-4">
            ยินดีต้อนรับสู่ <br className="d-md-none" />
            <span className="text-primary">Computer SLA System</span>
          </h1>

          <p className="text-muted mb-5">
            ระบบบริหารจัดการงานซ่อมและการให้บริการด้านคอมพิวเตอร์
          </p>

          <div className="d-grid gap-3 d-sm-flex justify-content-sm-center">
            <a href="/login" className="btn btn-primary btn-lg px-5 fw-semibold">
              ลงชื่อเข้าใช้งาน
            </a>
            <a href="/register" className="btn btn-outline-primary btn-lg px-5 fw-semibold">
              ลงทะเบียน
            </a>
          </div>

        </div>
      </div>
    </div>
  )
}

export default Home;
