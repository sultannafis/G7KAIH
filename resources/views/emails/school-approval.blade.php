<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4f46e5; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
        .status { display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; }
        .approved { background: #10b981; color: white; }
        .rejected { background: #ef4444; color: white; }
        .button { display: inline-block; padding: 12px 24px; background: #4f46e5; color: white; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $title }}</h1>
        </div>
        
        <div class="content">
            <p>Halo {{ $adminName }},</p>
            
            @if($status == 'approved')
            <p>Kami dengan senang hati menginformasikan bahwa pendaftaran sekolah <strong>{{ $schoolName }}</strong> telah <span class="status approved">DISETUJUI</span>.</p>
            
            <div style="background: #e0e7ff; padding: 15px; border-radius: 6px; margin: 20px 0;">
                <p><strong>Detail Sekolah:</strong></p>
                <p>Nama: {{ $schoolName }}</p>
                <p>NPSN: {{ $schoolNpsn }}</p>
                <p>Tanggal Approval: {{ $approvalDate }}</p>
            </div>
            
            <p>Sekarang Anda dapat mengakses dashboard sekolah Anda dengan menggunakan kredensial yang telah didaftarkan.</p>
            
            <p style="margin-top: 30px;">
                <a href="{{ $loginUrl }}" class="button">Login ke Dashboard</a>
            </p>
            
            @elseif($status == 'rejected')
            <p>Kami informasikan bahwa pendaftaran sekolah <strong>{{ $schoolName }}</strong> telah <span class="status rejected">DITOLAK</span>.</p>
            
            <div style="background: #fee2e2; padding: 15px; border-radius: 6px; margin: 20px 0;">
                <p><strong>Alasan Penolakan:</strong></p>
                <p>{{ $rejectionReason }}</p>
                <p><strong>Tanggal Penolakan:</strong> {{ $rejectionDate }}</p>
            </div>
            
            <p>Jika Anda merasa ini adalah kesalahan atau membutuhkan klarifikasi lebih lanjut, silakan hubungi tim support kami.</p>
            
            @endif
            
            <hr style="margin: 30px 0; border: none; border-top: 1px solid #e5e7eb;">
            
            <p style="font-size: 14px; color: #6b7280;">
                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.<br>
                Jika Anda memiliki pertanyaan, silakan hubungi tim support kami.
            </p>
        </div>
    </div>
</body>
</html>