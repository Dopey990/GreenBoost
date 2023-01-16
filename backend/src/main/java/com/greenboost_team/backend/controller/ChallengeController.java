package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.ChallengeDto;
import com.greenboost_team.backend.entity.AdviceEntity;
import com.greenboost_team.backend.entity.ChallengeEntity;
import com.greenboost_team.backend.entity.UserEntity;
import com.greenboost_team.backend.mapper.ChallengeMapper;
import com.greenboost_team.backend.repository.ChallengeRepository;
import com.greenboost_team.backend.repository.UserRepository;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import javax.annotation.Resource;
import java.util.List;
import java.util.Locale;
import java.util.Optional;
import java.util.stream.Collectors;

@RestController
@RequestMapping("/challenges")
public class ChallengeController {

    @Resource
    private ChallengeMapper challengeMapper;

    @Resource
    private ChallengeRepository challengeRepository;

    @Resource
    private UserRepository userRepository;

    @GetMapping("/getByCategory")
    public ResponseEntity<List<ChallengeDto>> getByCategory(@RequestParam String category, @RequestParam String language) {
        List<ChallengeEntity> entities = challengeRepository.findByCategory(category);

        if (!entities.isEmpty()) {
            return ResponseEntity.ok(entities.stream().map(entity -> challengeMapper.entityToDto(entity, language)).collect(Collectors.toList()));
        }
        else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }

    @GetMapping(value = "/getChallengeForUser", produces = { MediaType.APPLICATION_JSON_UTF8_VALUE })
    public ResponseEntity<List<ChallengeDto>> getChallengeForUser(@RequestParam(required = false) String category,
                                                                  @RequestParam String token) {
        List<ChallengeEntity> entities = category == null ? challengeRepository.findAll() : challengeRepository.findByCategory(category);
        UserEntity user = userRepository.findByToken(token);

        if (!entities.isEmpty()) {
            return ResponseEntity.ok(entities.stream().filter(challengeEntity -> !user.getDoneChallenges().contains(challengeEntity.getId())).map(entity -> challengeMapper.entityToDto(entity, user.getLanguage())).collect(Collectors.toList()));
        }
        else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }

    @PostMapping("/answerChallenge")
    public ResponseEntity<Integer> answerChallenge(@RequestParam String challengeId,
                                                   @RequestParam String answer,
                                                   @RequestParam String token) {
        Optional<ChallengeEntity> entity = challengeRepository.findById(challengeId);
        UserEntity userEntity = userRepository.findByToken(token);

        if (entity.isPresent() && userEntity != null) {
            userEntity.getDoneChallenges().add(challengeId);
            userRepository.save(userEntity);
            boolean isRight = entity.get().getAnswers() == null ? "true".equalsIgnoreCase(answer.strip()) : entity.get().getAnswers().stream().anyMatch(challengeAnswer -> challengeAnswer.toUpperCase(Locale.ROOT).equalsIgnoreCase(answer.strip()));

            return ResponseEntity.ok(isRight ? Integer.parseInt(entity.get().getScore()) : Integer.parseInt(entity.get().getScore()) * -1);
        }
        else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }

}
