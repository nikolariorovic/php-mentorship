# Student Booking System - Back-end Requirements

## Šta sam napravio na front-end-u:

### 1. **Student stranica** (`resources/view/student/index.php`)
- 3-step booking forma
- Padajuće liste za specializations, mentors, time slots
- Mentor info kartica
- Responsive dizajn

### 2. **CSS** (`public/css/student.css`)
- Modern dizajn sa gradijentima
- Animacije i hover efekti
- Responsive za mobile

### 3. **JavaScript** (`public/js/student.js`)
- Form validacija
- Step navigation
- Mock podaci za testiranje
- API pozivi (zakomentarisani)

## Šta trebaš da implementiraš na back-end-u:

### 1. **API Endpoints**

#### GET `/student/mentors`
- **Parametri:** `specialization_id` (int)
- **Response:**
```json
{
    "success": true,
    "mentors": [
        {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "biography": "Experienced PHP developer...",
            "price": 50.00
        }
    ]
}
```

#### GET `/student/slots`
- **Parametri:** `mentor_id` (int), `date` (YYYY-MM-DD)
- **Response:**
```json
{
    "success": true,
    "slots": [
        {
            "time": "09:00:00",
            "display_time": "09:00",
            "available": true
        }
    ]
}
```

#### POST `/student/book`
- **Parametri:** `mentor_id`, `date`, `time`
- **Response:**
```json
{
    "success": true,
    "message": "Session booked successfully!"
}
```

### 2. **Database tabela**

```sql
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    mentor_id INT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    status ENUM('confirmed', 'cancelled', 'completed') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (mentor_id) REFERENCES users(id),
    
    UNIQUE KEY unique_booking (mentor_id, date, time, status)
);
```

### 3. **Back-end logika**

#### **Load Specializations**
- Učitaj sve specializations iz `specializations` tabele
- Prikaži ih u padajućoj listi

#### **Load Mentors by Specialization**
- Query: `SELECT u.* FROM users u INNER JOIN user_specializations us ON u.id = us.user_id WHERE us.specialization_id = ? AND u.role = 'mentor'`
- Vrati mentor podatke (id, first_name, last_name, biography, price)

#### **Load Available Time Slots**
- Radno vreme: 9h-21h (termini: 9:00, 10:00, 11:00, ..., 20:00)
- Proveri da li je termin već zauzet u `bookings` tabeli
- Vrati samo slobodne termine

#### **Book Session**
- Proveri da li je termin još uvek slobodan
- Proveri da li je datum u budućnosti
- Proveri da li je termin u radnom vremenu (9h-21h)
- Kreiraj booking u `bookings` tabeli

### 4. **Validacije**

- **Specialization ID:** mora postojati u bazi
- **Mentor ID:** mora biti mentor sa tom specializacijom
- **Date:** mora biti u budućnosti
- **Time:** mora biti između 9h i 21h
- **Double booking:** sprečiti dupliranje termina

### 5. **Error handling**

- Vrati JSON response sa `success: false` i `message` za greške
- HTTP status kodovi: 400 za validaciju, 500 za server greške

### 6. **Session management**

- Proveri da li je korisnik ulogovan kao student
- Koristi `$_SESSION['user']['id']` za student_id

## Kako testirati:

1. **Otvori student stranicu** - treba da vidiš specializations
2. **Izaberi specialization** - treba da se učitaju mentori
3. **Izaberi mentora** - treba da vidiš mentor info
4. **Izaberi datum** - treba da se učitaju time slots
5. **Izaberi termin** - treba da možeš da book-uješ

Sve je spremno na front-end-u, samo implementiraj back-end API-je! 