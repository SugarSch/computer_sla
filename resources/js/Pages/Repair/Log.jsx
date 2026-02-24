import React from 'react';
import { Link, Head } from '@inertiajs/react';

export default function Log({ repair, timeline }) {
    return (
        <div className="container py-5">
            <Head title={`ประวัติการแจ้งซ่อม - ${repair.title}`} />
            
            <div className="d-flex justify-content-between align-items-center mb-4">
                <h4><i className="bi bi-clock-history me-2"></i>ประวัติการดำเนินการ</h4>
                <Link href={`/repair/${repair.id}`} className="btn btn-secondary btn-sm">
                    กลับหน้าข้อมูลการแจ้งซ่อม
                </Link>
            </div>

            <div className="card shadow-sm">
                <div className="card-header bg-white">
                    <strong>หัวข้อ:</strong> {repair.title}
                </div>
                <div className="card-body">
                    <div className="timeline">
                        {timeline.length > 0 ? timeline.map((item, index) => (
                            <div className="d-flex mb-4" key={index}>
                                <div className="flex-shrink-0">
                                    <div className={`rounded-circle bg-${item.type === 'equipment' ? 'warning' : 'primary'} text-white d-flex align-items-center justify-content-center`} 
                                         style={{ width: '40px', height: '40px' }}>
                                        <i className={`bi bi-${item.type === 'equipment' ? 'tools' : 'person-fill'}`}></i>
                                    </div>
                                </div>
                                <div className="ms-3 border-start ps-3 pb-2 w-100">
                                    <div className="d-flex justify-content-between">
                                        <h6 className="mb-0 fw-bold text-primary">{item.action}</h6>
                                        <small className="text-muted">
                                            {new Date(item.timestamp).toLocaleString('th-TH')}
                                        </small>
                                    </div>
                                    <p className="mb-1 text-dark">{item.message || '-'}</p>
                                    <small className="text-muted">
                                        <i className="bi bi-person me-1"></i>ดำเนินการโดย: {item.user}
                                    </small>
                                </div>
                            </div>
                        )) : (
                            <div className="text-center py-5 text-muted">ไม่พบข้อมูลการบันทึก</div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}