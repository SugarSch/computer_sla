import React from "react";
import { useForm } from '@inertiajs/react';

function RepairForm({action, repair, sla_priority}){

    const { data, setData, post, errors } = useForm({
        title: '',
        description: '',
        sla_priority_id: 3,
        attachments: []
    })

    const submit = e => {
        e.preventDefault();
        if(action == "edit"){
            post('/repair-edit');
        }else{
            post('/repair-create');
        }
    }
    let head = "สร้างใบแจ้งซ่อมใหม่";
    if(action == "edit"){
        head = "แก้ไขใบแจ้งซ่อม";
    }

    return (
        <div className="container pt-5">
            <h3 className="text-center mb-4">{head}</h3>
            <div className="card shadow p-4">
                <form onSubmit={submit} encType="multipart/form-data">
                    <input
                        className="form-control mb-3"
                        placeholder="หัวข้อ"
                        onChange={e => setData('title', e.target.value)}
                    />
                    <textarea
                        className="form-control mb-3"
                        placeholder="รายละเอียด"
                        rows="4"
                        onChange={e => setData('description', e.target.value)}
                    ></textarea>
                    <label className="form-label">ความเร่งด่วน</label>
                    <select className="form-select mb-2"
                        onChange={e => setData('sla_priority_id', e.target.value)}>
                        {sla_priority.map(sla => (
                            <option key={sla.id} value={sla.id}>{sla.name}</option>
                        ))}
                    </select>
                    <label className="form-label">ไฟล์แนบ</label>
                    <input
                        type="file"
                        className="form-control"
                        multiple
                        onChange={e => setData('attachments', Array.from(e.target.files))}
                    />
                    {errors['attachments.*'] && (
                        <div className="text-danger small">
                            {errors['attachments.*']}
                        </div>
                    )}
                    <div className="text-right pt-3">
                        <button className="btn btn-primary">สร้างใบแจ้งซ่อม</button>
                    </div>
                </form>
            </div>
        </div>
    );
}

export default RepairForm;