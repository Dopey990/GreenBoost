package com.greenboost_team.backend.dto;

import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

public class LoginDto {

    private String email;
    private String password;

    public LoginDto(String email, String password) {
        this.email = email;
        this.password = password;
    }
 
    public void setEmail(String email) {
        this.email = email;
    }
    public void setPassword(String password) {
        this.password = password;
    }
    
    @Override
    public String toString() {
        return "LoginDto [email=" + email + ", password=" + password + "]";
    }
    public String getEmail() {
        return email;
    }
    public String getPassword() {
        return password;
    }
    
}
