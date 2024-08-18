import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent {
  title = 'FrontEndAngular';

  constructor(private router: Router) {}

  isLoggedIn(): boolean {
    return !!localStorage.getItem('authToken'); // Cek apakah authToken ada di localStorage
  }

  get userName(): string {
    return localStorage.getItem('userName') || ''; // Ambil nama pengguna dari localStorage
  }

  logout() {
    localStorage.removeItem('authToken'); // Hapus authToken dari localStorage
    localStorage.removeItem('userName'); // Hapus nama pengguna dari localStorage
    this.router.navigate(['/']); // Arahkan ke halaman utama setelah logout
  }
}
