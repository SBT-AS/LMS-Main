<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate - {{ $certificate->course->title }}</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 100%;
            height: 100%;
            padding: 25px;
            box-sizing: border-box;
        }

        .outer-border {
            border: 10px solid #c5a059;
            padding: 6px;
        }

        .inner-border {
            border: 2px solid #10b981;
            padding: 50px 60px;
            text-align: center;
        }

        .title {
            font-size: 48px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
        }

        .subtitle {
            font-size: 18px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #c5a059;
            margin-top: 10px;
            margin-bottom: 45px;
        }

        .text-muted {
            font-size: 16px;
            color: #64748b;
            margin-bottom: 20px;
        }

        .student-name {
            font-size: 38px;
            font-weight: bold;
            color: #1e293b;
            border-bottom: 1px solid #ccc;
            display: inline-block;
            padding: 0 50px 10px;
            margin-bottom: 25px;
        }

        .course-title {
            font-size: 28px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 10px;
            margin-bottom: 60px;
        }

        table.footer {
            width: 100%;
            margin-top: 60px;
            border-collapse: collapse;
        }

        table.footer td {
            width: 33%;
            text-align: center;
            vertical-align: top;
        }

        .line {
            border-top: 1px solid #cbd5e1;
            width: 160px;
            margin: 0 auto 8px;
        }

        .label {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .sub-label {
            font-size: 10px;
            color: #94a3b8;
            text-transform: uppercase;
        }

        .seal {
            width: 80px;
            height: 80px;
            border: 2px solid #c5a059;
            border-radius: 50%;
            margin: 0 auto;
            line-height: 80px;
            font-size: 11px;
            font-weight: bold;
            color: #c5a059;
        }

        .certificate-id {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 40px;
        }
    </style>
</head>

<body>
<div class="page">
    <div class="outer-border">
        <div class="inner-border">

            <div class="title">Certificate</div>
            <div class="subtitle">Of Completion</div>

            <div class="text-muted">This certifies that</div>

            <div class="student-name">
                {{ $certificate->user->name }}
            </div>

            <div class="text-muted">
                has successfully completed the course
            </div>

            <div class="course-title">
                {{ $certificate->course->title }}
            </div>

            <table class="footer">
                <tr>
                    <td>
                        <div class="line"></div>
                        <div class="label">
                            {{ optional($certificate->issued_at)->format('M d, Y') }}
                        </div>
                        <div class="sub-label">Date</div>
                    </td>

                    <td>
                        <div class="seal">SEAL</div>
                    </td>

                    <td>
                        <div class="line"></div>
                        <div class="label">Educater Academy</div>
                        <div class="sub-label">Institution</div>
                    </td>
                </tr>
            </table>

            <div class="certificate-id">
                Certificate ID: {{ $certificate->certificate_number }}
            </div>

        </div>
    </div>
</div>
</body>
</html>
