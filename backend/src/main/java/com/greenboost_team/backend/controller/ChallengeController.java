package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.ChallengeDto;
import com.greenboost_team.backend.entity.AdviceEntity;
import com.greenboost_team.backend.entity.ChallengeEntity;
import com.greenboost_team.backend.mapper.ChallengeMapper;
import com.greenboost_team.backend.repository.ChallengeRepository;
import org.springframework.http.HttpStatus;
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

    @PostMapping("/answerChallenge")
    public ResponseEntity<Integer> answerChallenge(@RequestParam String challengeId, @RequestParam String answer) {
        Optional<ChallengeEntity> entity = challengeRepository.findById(challengeId);

        if (entity.isPresent()) {
            boolean isRight = entity.get().getAnswers().stream().anyMatch(challengeAnswer -> challengeAnswer.toUpperCase(Locale.ROOT).equalsIgnoreCase(answer.strip()));

            return ResponseEntity.ok(isRight ? Integer.parseInt(entity.get().getScore()) : 0);
        }
        else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }

}
