import React from "react";
import { useForm } from '@inertiajs/react'

function Login() {
    const { data, setData, post, errors } = useForm({
        username: '',
        password: '',
    })

    const submit = e => {
        e.preventDefault();
        post('/login');
    }

    return (
        <div className="container min-vh-100 d-flex align-items-center justify-content-center">
            <div className="col-12 col-sm-10 col-md-6 col-lg-4">
                <div className="card shadow p-4">
                    <h3 className="text-center mb-4">ฟอร์มลงชื่อเข้าใช้งาน</h3>

                    <form onSubmit={submit}>
                        <input
                        className="form-control mb-3"
                        placeholder="ชื่อผู้เข้าใช้งาน"
                        onChange={e => setData('username', e.target.value)}
                        />

                        <input
                        type="password"
                        className="form-control mb-4"
                        placeholder="รหัสผ่าน"
                        onChange={e => setData('password', e.target.value)}
                        />

                        <button className="btn btn-success w-100">ลงชื่อเข้าใช้งาน</button>
                    </form>
                </div>
                <div className="text-center mt-3">
                    <small>ยังไม่มีบัญชี? <a href="/register">ลงทะเบียน</a></small>
                </div>
            </div>
        </div>
    )
}

export default Login;