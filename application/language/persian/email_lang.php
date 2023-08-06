<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang["email_must_be_array"]="روش اعتبارسنجی ایمیل باید یک آرایه ارسال شود.";
$lang["email_invalid_address"]="آدرس ایمیل نامعتبر: %s";
$lang["email_attachment_missing"]="نمی توان پیوست ایمیل زیر را پیدا کرد: %s";
$lang["email_attachment_unreadable"]="این پیوست باز نمی شود: %s";
$lang["email_no_from"]= "ایمیل بدون فرستنده ارسال نمی گردد";
$lang["email_no_recipients"]="شما باید گیرندگان را شامل کنید: به، رونوشت، یا رونوشت مخفی";
$lang["email_send_failure_phpmail"]="ارسال ایمیل با استفاده از PHP mail (). سرور شما ممکن است برای ارسال نامه با استفاده از این روش پیکربندی نشده باشد.";
$lang["email_send_failure_sendmail"]="امکان ارسال ایمیل با استفاده از PHP Sendmail وجود ندارد. سرور شما ممکن است برای ارسال نامه با استفاده از این روش پیکربندی نشده باشد.";
$lang["email_send_failure_smtp"]="امکان ارسال ایمیل با استفاده از PHP SMTP وجود ندارد. سرور شما ممکن است برای ارسال نامه با استفاده از این روش پیکربندی نشده باشد.";
$lang["email_sent"]="پیام شما با استفاده از پروتکل زیر با موفقیت ارسال شد: %s";
$lang["email_no_socket"]="امکان باز کردن سوکت برای Sendmail وجود ندارد. لطفا تنظیمات را بررسی کنید.";
$lang["email_no_hostname"]="شما نام میزبان SMTP را مشخص نکرده اید.";
$lang["email_smtp_error"]="خطای SMTP زیر روی داد: %s";
$lang["email_no_smtp_unpw"]="خطا: شما باید یک نام کاربری و رمز عبور SMTP اختصاص دهید.";
$lang["email_failed_smtp_login"]="دستور AUTH LOGIN ارسال نشد. خطا: %s";
$lang["email_smtp_auth_un"]="نام کاربری تأیید نشد. خطا: %s";
$lang["email_smtp_auth_pw"]="رمز عبور تأیید نشد. خطا: %s";
$lang["email_smtp_data_failure"]="امکان ارسال داده وجود ندارد: %s";
$lang["email_exit_status"]="کد وضعیت خروج: %s";

