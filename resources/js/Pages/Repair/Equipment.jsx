import React from "react";
import { useForm, Link } from '@inertiajs/react';

function Equipment({ repair }) {
    const { data, setData, post, errors, processing } = useForm({
        item_name: '',
        cost: ''
    });

    const submit = (e) => {
        e.preventDefault();
        post("/equipment-request/" + repair.id);
    };

    return (
        <div className="container pt-5">
            <h3 className="text-center mb-4">ฟอร์มร้องขออุปกรณ์/อะไหล่</h3>
            
            <div className="card shadow p-4 col-md-8 mx-auto">
                <div className="alert alert-info">
                    <strong>หมายเลขใบงาน:</strong> {repair.id} : {repair.title}
                </div>

                <form onSubmit={submit}>
                    <div className="mb-3">
                        <label className="form-label">ชื่อรายการอุปกรณ์/อะไหล่</label>
                        <input 
                            type="text"
                            className={`form-control ${errors.item_name ? 'is-invalid' : ''}`}
                            placeholder="เช่น Hard Disk 1TB, RAM 8GB"
                            value={data.item_name}
                            onChange={e => setData('item_name', e.target.value)}
                        />
                        {errors.item_name && <div className="invalid-feedback">{errors.item_name}</div>}
                    </div>

                    <div className="mb-3">
                        <label className="form-label">ราคาประเมิน (บาท)</label>
                        <input 
                            type="number"
                            step="0.01"
                            className={`form-control ${errors.cost ? 'is-invalid' : ''}`}
                            placeholder="0.00"
                            value={data.cost}
                            onChange={e => setData('cost', e.target.value)}
                        />
                        {errors.cost && <div className="invalid-feedback">{errors.cost}</div>}
                    </div>

                    <div className="d-flex justify-content-between pt-3">
                        <Link href={`/repair/${repair.id}`} className="btn btn-secondary">
                            ยกเลิก
                        </Link>
                        <button type="submit" className="btn btn-primary" disabled={processing}>
                            ส่งคำขออนุมัติ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

export default Equipment;